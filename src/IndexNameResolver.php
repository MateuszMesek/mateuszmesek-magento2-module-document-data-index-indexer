<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer;

use MateuszMesek\DocumentDataIndexIndexerApi\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\IndexNameResolverInterface;

class IndexNameResolver implements IndexNameResolverInterface
{
    private DimensionResolverInterface $documentNameResolver;
    private IndexNameResolverFactory $indexNameResolverFactory;

    public function __construct(
        DimensionResolverInterface $documentNameResolver,
        IndexNameResolverFactory $indexNameResolverFactory
    )
    {
        $this->documentNameResolver = $documentNameResolver;
        $this->indexNameResolverFactory = $indexNameResolverFactory;
    }

    public function resolve(array $dimensions): string
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        return $this->indexNameResolverFactory->get($documentName)->resolve($dimensions);
    }
}
