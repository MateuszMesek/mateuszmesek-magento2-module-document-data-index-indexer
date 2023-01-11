<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use MateuszMesek\DocumentDataIndexIndexerApi\Model\DataResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;
use Traversable;

class DataResolver implements DataResolverInterface
{
    public function __construct(
        private readonly DimensionResolverInterface $documentNameResolver,
        private readonly DataResolverFactory        $dataResolverFactory
    )
    {
    }

    public function resolve(array $dimensions, Traversable $entityIds): Traversable
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        return $this->dataResolverFactory->create($documentName)->resolve($dimensions, $entityIds);
    }
}
