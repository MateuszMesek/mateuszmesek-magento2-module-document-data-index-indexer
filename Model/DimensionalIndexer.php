<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use ArrayIterator;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\DataResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\SaveHandlerInterface;
use Traversable;

class DimensionalIndexer implements DimensionalIndexerInterface
{
    public function __construct(
        private readonly SaveHandlerInterface  $saveHandler,
        private readonly DataResolverInterface $dataResolver,
        private readonly int                   $batchSize = 100
    )
    {
    }

    public function executeByDimensions(array $dimensions, Traversable $entityIds): void
    {
        if (!$this->saveHandler->isAvailable($dimensions)) {
            return;
        }

        $batchesIds = $this->batchIds($entityIds);

        foreach ($batchesIds as $batchIds) {
            $documents = $this->dataResolver->resolve($dimensions, $batchIds);

            $toDelete = [];
            $toSave = [];

            foreach ($documents as $documentId => $document) {
                if (empty($document)) {
                    $toDelete[$documentId] = $document;
                } else {
                    $toSave[$documentId] = $document;
                }
            }

            if (!empty($toDelete)) {
                $this->saveHandler->deleteIndex($dimensions, new ArrayIterator($toDelete));
            }

            if (!empty($toSave)) {
                $this->saveHandler->saveIndex($dimensions, new ArrayIterator($toSave));
            }
        }
    }

    private function batchIds(Traversable $entityIds): Traversable
    {
        $batchSize = 0;
        $batchIds = [];

        foreach ($entityIds as $entityId) {
            $batchSize++;
            $batchIds[] = $entityId;

            if ($this->batchSize === $batchSize) {
                yield new ArrayIterator($batchIds);

                $batchSize = 0;
                $batchIds = [];
            }
        }

        if ($batchSize) {
            yield new ArrayIterator($batchIds);
        }
    }
}
