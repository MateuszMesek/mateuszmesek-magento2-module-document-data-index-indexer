<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\IndexNameResolver\PlaceholderResolver;

use MateuszMesek\DocumentDataIndexIndexer\Model\IndexNameResolver\PlaceholderResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;

class StoreId implements PlaceholderResolverInterface
{
    public function __construct(
        private readonly DimensionResolverInterface $storeIdResolver
    )
    {
    }

    public function resolve(array $dimensions = []): string
    {
        return (string)$this->storeIdResolver->resolve($dimensions);
    }
}
