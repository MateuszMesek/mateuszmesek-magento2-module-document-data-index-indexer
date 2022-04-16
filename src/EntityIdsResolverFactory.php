<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Config\EntityIdsResolverInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\EntityIdsResolverInterface;

class EntityIdsResolverFactory
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

    public function create(string $documentName): EntityIdsResolverInterface
    {
        $type = $this->config->getEntityIdsResolver($documentName);

        if (null === $type) {
            throw new InvalidArgumentException(
                "Entity ids resolver for '$documentName' document data is not configured"
            );
        }

        $entityIdsResolver = $this->objectManager->create($type);

        if (!$entityIdsResolver instanceof EntityIdsResolverInterface) {
            $interfaceName = EntityIdsResolverInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $entityIdsResolver;
    }
}
