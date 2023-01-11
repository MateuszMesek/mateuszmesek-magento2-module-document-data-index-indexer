<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\Action;

use ArrayIterator;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use Magento\Framework\Indexer\DimensionProviderInterface;

class Row implements ExecutorInterface
{
    public const TYPE = 'row';

    public function __construct(
        private readonly DimensionProviderInterface  $dimensionProvider,
        private readonly DimensionalIndexerInterface $dimensionalIndexer
    )
    {
    }

    public function execute($id): void
    {
        foreach ($this->dimensionProvider->getIterator() as $dimensions) {
            $this->dimensionalIndexer->executeByDimensions($dimensions, new ArrayIterator([$id]));
        }
    }
}
