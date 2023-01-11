<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\IndexNameResolver;

interface PlaceholderResolverInterface
{
    public function resolve(array $dimensions = []): string;
}
