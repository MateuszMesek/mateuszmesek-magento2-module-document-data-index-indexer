<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Query\Generator as QueryGenerator;
use MateuszMesek\DocumentDataIndexIndexer\Model\ResourceModel\Ids as Resource;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IdsResolverInterface;
use Traversable;

class IdsResolver implements IdsResolverInterface
{
    public function __construct(
        private readonly Resource                   $resource,
        private readonly DimensionResolverInterface $documentNameResolver,
        private readonly IdsResolverInterface       $entityIdsResolver,
        private readonly IdsResolverInterface       $indexIdsResolver,
        private readonly QueryGenerator             $queryGenerator,
        private readonly int                        $batchSize = 100
    )
    {
    }

    public function resolve(array $dimensions): Traversable
    {
        try {
            $this->createIdsTable($dimensions);

            $this->addIndexedIdsToQueue($dimensions);

            yield from $this->getEntityIds($dimensions);

            yield from $this->getIndexedIds($dimensions);
        } finally {
            $this->dropIdsTable($dimensions);
        }
    }

    private function getIdsTable(string $documentName): string
    {
        return $this->resource->getDocumentDataTable($documentName);
    }

    private function batchIds(Traversable $inputIds): Traversable
    {
        $i = 0;
        $outputIds = [];

        foreach ($inputIds as $id) {
            $outputIds[] = $id;

            if (++$i !== $this->batchSize) {
                continue;
            }

            yield $outputIds;

            $i = 0;
            $outputIds = [];
        }

        if (!empty($outputIds)) {
            yield $outputIds;
        }
    }

    private function createIdsTable(array $dimensions): void
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);
        $indexTableName = $this->getIdsTable($documentName);
        $patternTableName = $this->resource->getMainTable();

        $connection = $this->resource->getConnection();
        $connection->createTemporaryTableLike(
            $indexTableName,
            $patternTableName
        );
    }

    private function addIndexedIdsToQueue(array $dimensions): void
    {
        $connection = $this->resource->getConnection();
        $documentName = $this->documentNameResolver->resolve($dimensions);

        $indexTableName = $this->getIdsTable($documentName);
        $indexIds = $this->indexIdsResolver->resolve($dimensions);

        foreach ($this->batchIds($indexIds) as $documentIds) {
            $data = [];

            foreach ($documentIds as $documentId) {
                $data[] = ['document_id' => $documentId];
            }

            $connection->insertArray(
                $indexTableName,
                ['document_id'],
                $data,
                AdapterInterface::INSERT_IGNORE
            );
        }
    }

    private function getEntityIds(array $dimensions): Traversable
    {
        $connection = $this->resource->getConnection();
        $documentName = $this->documentNameResolver->resolve($dimensions);

        $indexTableName = $this->getIdsTable($documentName);
        $entityIds = $this->entityIdsResolver->resolve($dimensions);

        foreach ($this->batchIds($entityIds) as $documentIds) {
            $connection->delete(
                $indexTableName,
                ['document_id IN (?)' => $documentIds]
            );

            yield from $documentIds;
        }
    }

    private function getIndexedIds(array $dimensions): Traversable
    {
        $connection = $this->resource->getConnection();
        $documentName = $this->documentNameResolver->resolve($dimensions);

        $indexTableName = $this->getIdsTable($documentName);

        $select = ($connection->select())
            ->from($indexTableName, ['document_id', 'id']);

        $queries = $this->queryGenerator->generate(
            'id',
            $select,
            $this->batchSize
        );

        foreach ($queries as $query) {
            yield from $connection->fetchCol($query);
        }
    }

    private function dropIdsTable(array $dimensions): void
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);
        $indexTableName = $this->getIdsTable($documentName);

        $connection = $this->resource->getConnection();
        $connection->dropTable($indexTableName);
    }
}
