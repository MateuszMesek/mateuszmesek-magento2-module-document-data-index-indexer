<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\EntityIdsResolverInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IdsResolverInterface;

class EntityIdsResolverFactory
{
    public function __construct(
        private readonly ConfigInterface        $config,
        private readonly ObjectManagerInterface $objectManager,
        private readonly array                  $arguments = []
    )
    {
    }

    public function create(string $documentName): IdsResolverInterface
    {
        $type = $this->config->getEntityIdsResolver($documentName);

        if (null === $type) {
            throw new InvalidArgumentException(
                "Entity ids resolver for '$documentName' document data is not configured"
            );
        }

        $entityIdsResolver = $this->objectManager->create(
            $type,
            $this->arguments
        );

        if (!$entityIdsResolver instanceof IdsResolverInterface) {
            $interfaceName = IdsResolverInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $entityIdsResolver;
    }
}
