<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer;

use InvalidArgumentException;
use Magento\Framework\Indexer\DimensionProviderInterface;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Config\EntityIdsResolverInterface as ConfigInterface;

class DimensionProviderFactory
{
    private ConfigInterface $config;
    private ObjectManagerInterface $objectManager;

    public function __construct(
        ConfigInterface        $config,
        ObjectManagerInterface $objectManager
    )
    {
        $this->config = $config;
        $this->objectManager = $objectManager;
    }

    public function create(string $documentName): DimensionProviderInterface
    {
        $type = $this->config->getDimensionProvider($documentName);

        if (null === $type) {
            throw new InvalidArgumentException(
                "Dimension provider for '$documentName' document data is not configured"
            );
        }

        $provider = $this->objectManager->create($type);

        if (!$provider instanceof DimensionProviderInterface) {
            $interfaceName = DimensionProviderInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $provider;
    }
}
