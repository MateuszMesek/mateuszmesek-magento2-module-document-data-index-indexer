<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\DimensionResolver;

use Magento\Store\Model\StoreDimensionProvider;
use Magento\Store\Model\StoreManagerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;

class StoreIdResolver implements DimensionResolverInterface
{
    public function __construct(
        private readonly StoreManagerInterface $storeManager
    )
    {
    }

    /**
     * @param \Magento\Framework\Indexer\Dimension[] $dimensions
     * @return int|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function resolve(array $dimensions): ?int
    {
        if (!isset($dimensions[StoreDimensionProvider::DIMENSION_NAME])) {
            return null;
        }

        $store = $dimensions[StoreDimensionProvider::DIMENSION_NAME]->getValue();

        return (int)$this->storeManager->getStore($store)->getId();
    }
}
