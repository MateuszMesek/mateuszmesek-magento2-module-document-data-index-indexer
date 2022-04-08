<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Action;

use InvalidArgumentException;
use Magento\Framework\Indexer\DimensionProviderInterface;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexer\Config;

class DimensionProviderFactory
{
    private Config $config;
    private ObjectManagerInterface $objectManager;

    public function __construct(
        Config $config,
        ObjectManagerInterface $objectManager
    )
    {
        $this->config = $config;
        $this->objectManager = $objectManager;
    }

    public function create(string $documentName): DimensionProviderInterface
    {
        $type = $this->config->getDimensionProvider($documentName);

        $dimensionProvider = $this->objectManager->create($type);

        if (!$dimensionProvider instanceof DimensionProviderInterface) {
            $interfaceName = DimensionProviderInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $dimensionProvider;
    }
}
