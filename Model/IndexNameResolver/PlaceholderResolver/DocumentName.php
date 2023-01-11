<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\IndexNameResolver\PlaceholderResolver;

use MateuszMesek\DocumentDataIndexIndexer\Model\IndexNameResolver\PlaceholderResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;

class DocumentName implements PlaceholderResolverInterface
{
    public function __construct(
        private readonly DimensionResolverInterface $documentNameResolver
    )
    {
    }

    public function resolve(array $dimensions = []): string
    {
        return $this->documentNameResolver->resolve($dimensions);
    }
}
