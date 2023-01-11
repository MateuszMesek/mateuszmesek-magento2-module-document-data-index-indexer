<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\IndexNameResolver;

use InvalidArgumentException;
use Magento\Framework\ObjectManager\TMap;
use Magento\Framework\ObjectManager\TMapFactory;

class PlaceholderResolverPool
{
    private TMap $placeholderByCode;

    public function __construct(
        TMapFactory $TMapFactory,
        array $placeholders = []
    )
    {
        $this->placeholderByCode = $TMapFactory->createSharedObjectsMap([
            'type' => PlaceholderResolverInterface::class,
            'array' => $placeholders
        ]);
    }

    public function get(string $placeholder): PlaceholderResolverInterface
    {
        if (!isset($this->placeholderByCode[$placeholder])) {
            throw new InvalidArgumentException(sprintf(
                'Placeholder "%s" not found',
                $placeholder
            ));
        }

        return $this->placeholderByCode[$placeholder];
    }
}
