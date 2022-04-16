<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\DimensionProvider;

use Magento\Framework\Indexer\DimensionProviderInterface;
use MateuszMesek\DocumentDataIndexIndexer\Dimension\Factory;
use Traversable;

class WithNodePathsProvider implements DimensionProviderInterface
{
    public const DIMENSION_NAME = 'node-paths';

    private array $nodePaths;
    private DimensionProviderInterface $dimensionProvider;
    private Factory $factory;

    public function __construct(
        array                      $nodePaths,
        DimensionProviderInterface $dimensionProvider,
        Factory                    $factory
    )
    {
        $this->nodePaths = $nodePaths;
        $this->dimensionProvider = $dimensionProvider;
        $this->factory = $factory;
    }

    public function getIterator(): Traversable
    {
        foreach ($this->dimensionProvider->getIterator() as $dimensions) {
            $dimension = $this->factory->create(self::DIMENSION_NAME, $this->nodePaths);

            $dimensions[$dimension->getName()] = $dimension;

            yield $dimensions;
        }
    }
}
