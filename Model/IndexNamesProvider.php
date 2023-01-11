<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use MateuszMesek\DocumentDataIndexIndexer\Model\Action\DimensionProviderFactory;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IndexNameResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IndexNamesProviderInterface;
use Traversable;

class IndexNamesProvider implements IndexNamesProviderInterface
{
    public function __construct(
        private readonly DimensionProviderFactory   $dimensionProviderFactory,
        private readonly IndexNameResolverInterface $indexNameResolver,
        private readonly string                     $documentName
    )
    {
    }

    public function getIndexNames(): Traversable
    {
        $dimensionProvider = $this->dimensionProviderFactory->create($this->documentName);

        foreach ($dimensionProvider->getIterator() as $dimensions) {
            yield $this->indexNameResolver->resolve($dimensions);
        }
    }
}
