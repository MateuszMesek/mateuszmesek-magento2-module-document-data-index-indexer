<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\Dimension;

use Magento\Framework\Indexer\Dimension;
use Magento\Framework\Indexer\DimensionFactory;

class Factory
{
    public function __construct(
        private readonly DimensionFactory $dimensionFactory,
        private readonly array            $factories = []
    )
    {
    }

    public function create(string $name, $value): Dimension
    {
        if (isset($this->factories[$name])) {
            return $this->factories[$name]->create($value);
        }

        return $this->dimensionFactory->create($name, (string)$value);
    }
}
