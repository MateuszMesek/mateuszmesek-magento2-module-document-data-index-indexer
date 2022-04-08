<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Action;

use ArrayIterator;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use Magento\Framework\Indexer\DimensionProviderInterface;

class Rows implements ExecutorInterface
{
    public const TYPE = 'rows';

    private DimensionProviderInterface $dimensionProvider;
    private DimensionalIndexerInterface $dimensionalIndexer;

    public function __construct(
        DimensionProviderInterface $dimensionProvider,
        DimensionalIndexerInterface $dimensionalIndexer
    )
    {
        $this->dimensionProvider = $dimensionProvider;
        $this->dimensionalIndexer = $dimensionalIndexer;
    }

    public function execute(array $ids): void
    {
        foreach ($this->dimensionProvider->getIterator() as $dimensions) {
            $this->dimensionalIndexer->executeByDimensions($dimensions, new ArrayIterator($ids));
        }
    }
}
