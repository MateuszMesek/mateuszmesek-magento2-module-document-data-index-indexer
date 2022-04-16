<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Config\DataResolverInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\DataResolverInterface;

class DataResolverFactory
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

    public function create(string $documentName): DataResolverInterface
    {
        $type = $this->config->getDataResolver($documentName);

        if (null === $type) {
            throw new InvalidArgumentException(
                "Data resolver for '$documentName' document data is not configured"
            );
        }

        $dataResolver = $this->objectManager->create($type);

        if (!$dataResolver instanceof DataResolverInterface) {
            $interfaceName = DataResolverInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $dataResolver;
    }
}
