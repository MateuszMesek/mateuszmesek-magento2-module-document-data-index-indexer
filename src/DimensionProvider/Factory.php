<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\DimensionProvider;

use Magento\Framework\Indexer\Dimension;
use Magento\Framework\Indexer\DimensionFactory;

class Factory
{
    private DimensionFactory $dimensionFactory;
    private array $factories;

    public function __construct(
        DimensionFactory $dimensionFactory,
        array $factories = []
    )
    {
        $this->dimensionFactory = $dimensionFactory;
        $this->factories = $factories;
    }

    public function create(string $name, $value): Dimension
    {
        if (isset($this->factories[$name])) {
            return $this->factories[$name]->create($name, $value);
        }

        return $this->dimensionFactory->create($name, (string)$value);
    }
}
