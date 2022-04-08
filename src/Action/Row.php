<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Action;

use ArrayIterator;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use Magento\Framework\Indexer\DimensionProviderInterface;

class Row implements ExecutorInterface
{
    public const TYPE = 'row';

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

    public function execute($id): void
    {
        foreach ($this->dimensionProvider->getIterator() as $dimensions) {
            $this->dimensionalIndexer->executeByDimensions($dimensions, new ArrayIterator([$id]));
        }
    }
}
