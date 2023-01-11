<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\DimensionResolver;

use Magento\Framework\Serialize\SerializerInterface;
use MateuszMesek\DocumentDataIndexIndexer\Model\DimensionProvider\WithNodePathsProvider;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;

class NodePathsResolver implements DimensionResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer
    )
    {
    }

    /**
     * @param \Magento\Framework\Indexer\Dimension[] $dimensions
     * @return string[]|null
     */
    public function resolve(array $dimensions): ?array
    {
        if (!isset($dimensions[WithNodePathsProvider::DIMENSION_NAME])) {
            return null;
        }

        $value = $this->serializer->unserialize(
            $dimensions[WithNodePathsProvider::DIMENSION_NAME]->getValue()
        );

        if (empty($value)) {
            $value = null;
        }

        return $value;
    }
}
