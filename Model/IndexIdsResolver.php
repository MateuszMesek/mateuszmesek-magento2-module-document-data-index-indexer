<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IdsResolverInterface;
use Traversable;

class IndexIdsResolver implements IdsResolverInterface
{
    public function __construct(
        private readonly DimensionResolverInterface $documentNameResolver,
        private readonly IndexIdsResolverFactory    $indexIdsResolverFactory
    )
    {
    }

    public function resolve(array $dimensions): Traversable
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        return $this->indexIdsResolverFactory->create($documentName)->resolve($dimensions);
    }
}
