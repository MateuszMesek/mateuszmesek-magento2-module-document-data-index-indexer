<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer;

use ArrayIterator;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\DataResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\SaveHandlerInterface;
use Traversable;

class DimensionalIndexer implements DimensionalIndexerInterface
{
    private SaveHandlerInterface $saveHandler;
    private DataResolverInterface $dataResolver;

    public function __construct(
        SaveHandlerInterface  $saveHandler,
        DataResolverInterface $dataResolver
    )
    {
        $this->saveHandler = $saveHandler;
        $this->dataResolver = $dataResolver;
    }

    public function executeByDimensions(array $dimensions, Traversable $entityIds): void
    {
        if (!$this->saveHandler->isAvailable($dimensions)) {
            return;
        }

        $documents = $this->dataResolver->resolve($dimensions, $entityIds);

        $toDelete = [];
        $toSave = [];

        foreach ($documents as $documentId => $document) {
            if (empty($document)) {
                $toDelete[] = $documentId;
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
