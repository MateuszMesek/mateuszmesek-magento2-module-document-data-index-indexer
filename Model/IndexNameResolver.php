<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IndexNameResolverInterface;

class IndexNameResolver implements IndexNameResolverInterface
{
    public function __construct(
        private readonly DimensionResolverInterface $documentNameResolver,
        private readonly IndexNameResolverFactory   $indexNameResolverFactory
    )
    {
    }

    public function resolve(array $dimensions): string
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        return $this->indexNameResolverFactory->get($documentName)->resolve($dimensions);
    }
}
