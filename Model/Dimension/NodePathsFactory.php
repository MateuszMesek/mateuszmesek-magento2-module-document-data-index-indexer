<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\Dimension;

use Magento\Framework\Indexer\Dimension;
use Magento\Framework\Indexer\DimensionFactory;
use Magento\Framework\Serialize\SerializerInterface;
use MateuszMesek\DocumentDataIndexIndexer\Model\DimensionProvider\WithNodePathsProvider;

class NodePathsFactory
{
    public function __construct(
        private readonly DimensionFactory    $dimensionFactory,
        private readonly SerializerInterface $serializer
    )
    {
    }

    public function create(array $nodePaths): Dimension
    {
        return $this->dimensionFactory->create(
            WithNodePathsProvider::DIMENSION_NAME,
            $this->serializer->serialize($nodePaths)
        );
    }
}
