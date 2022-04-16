<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer;

use MateuszMesek\DocumentDataIndexIndexerApi\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\EntityIdsResolverInterface;
use Traversable;

class EntityIdsResolver implements EntityIdsResolverInterface
{
    private DimensionResolverInterface $documentNameResolver;
    private EntityIdsResolverFactory $entityIdsResolverFactory;

    public function __construct(
        DimensionResolverInterface $documentNameResolver,
        EntityIdsResolverFactory $entityIdsResolverFactory
    )
    {
        $this->documentNameResolver = $documentNameResolver;
        $this->entityIdsResolverFactory = $entityIdsResolverFactory;
    }

    public function resolve(array $dimensions): Traversable
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        return $this->entityIdsResolverFactory->create($documentName)->resolve($dimensions);
    }
}
