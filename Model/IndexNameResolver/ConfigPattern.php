<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\IndexNameResolver;

use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\IndexNamePatternInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IndexNameResolverInterface;

class ConfigPattern implements IndexNameResolverInterface
{
    public function __construct(
        private readonly DimensionResolverInterface $documentNameResolver,
        private readonly IndexNamePatternInterface  $config,
        private readonly PlaceholderResolverPool    $placeholderResolverPool
    )
    {
    }

    public function resolve(array $dimensions = []): string
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        $indexNamePattern = $this->config->getIndexNamePattern($documentName);

        return preg_replace_callback(
            '~{{(.*)}}~U',
            function ($matches) use ($dimensions) {
                return $this->placeholderResolverPool->get($matches[1])->resolve($dimensions);
            },
            $indexNamePattern
        );
    }
}
