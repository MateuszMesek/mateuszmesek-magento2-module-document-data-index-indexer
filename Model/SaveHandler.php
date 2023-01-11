<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\SaveHandlerInterface;
use Traversable;

class SaveHandler implements SaveHandlerInterface
{
    public function __construct(
        private readonly DimensionResolverInterface $documentNameResolver,
        private readonly SaveHandlerFactory         $saveHandlerFactory
    )
    {
    }

    public function isAvailable(array $dimensions = []): bool
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        return $this->saveHandlerFactory->get($documentName)->isAvailable($dimensions);
    }

    public function saveIndex(array $dimensions, Traversable $documents): void
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        $this->saveHandlerFactory->get($documentName)->saveIndex($dimensions, $documents);
    }

    public function deleteIndex(array $dimensions, Traversable $documents): void
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        $this->saveHandlerFactory->get($documentName)->deleteIndex($dimensions, $documents);
    }
}
