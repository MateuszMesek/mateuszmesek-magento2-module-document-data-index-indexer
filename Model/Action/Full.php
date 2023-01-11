<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\Action;

use Magento\Framework\Indexer\DimensionalIndexerInterface;
use Magento\Framework\Indexer\DimensionProviderInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IdsResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IndexStructureBuilderInterface;

class Full implements ExecutorInterface
{
    public const TYPE = 'full';

    public function __construct(
        private readonly DimensionProviderInterface     $dimensionProvider,
        private readonly IndexStructureBuilderInterface $indexStructureBuilder,
        private readonly IdsResolverInterface           $idsResolver,
        private readonly DimensionalIndexerInterface    $dimensionalIndexer
    )
    {
    }

    public function execute(): void
    {
        foreach ($this->dimensionProvider->getIterator() as $dimensions) {
            $this->indexStructureBuilder->build($dimensions);

            $ids = $this->idsResolver->resolve($dimensions);

            $this->dimensionalIndexer->executeByDimensions($dimensions, $ids);
        }
    }
}
