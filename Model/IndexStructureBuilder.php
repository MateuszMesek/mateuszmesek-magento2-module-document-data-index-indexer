<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IndexStructureBuilderInterface;

class IndexStructureBuilder implements IndexStructureBuilderInterface
{
    public function __construct(
        private readonly DimensionResolverInterface   $documentNameResolver,
        private readonly IndexStructureBuilderFactory $indexStructureBuilderFactory
    )
    {
    }

    public function build(array $dimensions = []): void
    {
        $documentName = $this->documentNameResolver->resolve($dimensions);

        $this->indexStructureBuilderFactory->get($documentName)->build($dimensions);
    }
}
