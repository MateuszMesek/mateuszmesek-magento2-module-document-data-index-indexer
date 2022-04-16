<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Dimension;

use Magento\Framework\Indexer\Dimension;
use Magento\Framework\Indexer\DimensionFactory;
use Magento\Framework\Serialize\SerializerInterface;
use MateuszMesek\DocumentDataIndexIndexer\DimensionProvider\WithNodePathsProvider;

class NodePathsFactory
{
    private DimensionFactory $dimensionFactory;
    private SerializerInterface $serializer;

    public function __construct(
        DimensionFactory $dimensionFactory,
        SerializerInterface $serializer
    )
    {
        $this->dimensionFactory = $dimensionFactory;
        $this->serializer = $serializer;
    }

    public function create(array $nodePaths): Dimension
    {
        return $this->dimensionFactory->create(
            WithNodePathsProvider::DIMENSION_NAME,
            $this->serializer->serialize($nodePaths)
        );
    }
}
