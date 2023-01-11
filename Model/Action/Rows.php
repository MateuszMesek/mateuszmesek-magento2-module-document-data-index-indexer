<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\Action;

use ArrayIterator;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use Magento\Framework\Indexer\DimensionProviderInterface;

class Rows implements ExecutorInterface
{
    public const TYPE = 'rows';

    public function __construct(
        private readonly DimensionProviderInterface  $dimensionProvider,
        private readonly DimensionalIndexerInterface $dimensionalIndexer
    )
    {
    }

    public function execute(array $ids): void
    {
        foreach ($this->dimensionProvider->getIterator() as $dimensions) {
            $this->dimensionalIndexer->executeByDimensions($dimensions, new ArrayIterator($ids));
        }
    }
}
