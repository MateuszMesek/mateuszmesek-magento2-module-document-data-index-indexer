<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\ReadHandlerInterface;
use Traversable;

class ReadHandler implements ReadHandlerInterface
{
    public function __construct(
        private readonly DimensionResolverInterface $documentNameResolver,
        private readonly ReadHandlerFactory         $readHandlerFactory
    )
    {
    }

    public function isAvailable(array $dimensions = []): bool
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        return $this->readHandlerFactory->get($documentName)->isAvailable($dimensions);
    }

    public function readIndex(array $dimensions, ?SearchCriteriaInterface $searchCriteria = null): Traversable
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        return $this->readHandlerFactory->get($documentName)->readIndex($dimensions, $searchCriteria);
    }
}
