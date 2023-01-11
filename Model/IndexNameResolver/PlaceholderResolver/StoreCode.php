<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\IndexNameResolver\PlaceholderResolver;

use Magento\Store\Model\StoreManagerInterface;
use MateuszMesek\DocumentDataIndexIndexer\Model\IndexNameResolver\PlaceholderResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\DimensionResolverInterface;

class StoreCode implements PlaceholderResolverInterface
{
    public function __construct(
        private readonly DimensionResolverInterface $storeIdResolver,
        private readonly StoreManagerInterface $storeManager
    )
    {
    }

    public function resolve(array $dimensions = []): string
    {
        $storeId = $this->storeIdResolver->resolve($dimensions);

        return $this->storeManager->getStore($storeId)->getCode();
    }
}
