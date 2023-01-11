<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use InvalidArgumentException;
use Magento\Framework\Indexer\DimensionProviderInterface;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\EntityIdsResolverInterface as ConfigInterface;

class DimensionProviderFactory
{
    public function __construct(
        private readonly ConfigInterface        $config,
        private readonly ObjectManagerInterface $objectManager,
        private readonly array                  $arguments = []
    )
    {
    }

    public function create(string $documentName): DimensionProviderInterface
    {
        $type = $this->config->getDimensionProvider($documentName);

        if (null === $type) {
            throw new InvalidArgumentException(
                "Dimension provider for '$documentName' document data is not configured"
            );
        }

        $provider = $this->objectManager->create(
            $type,
            $this->arguments
        );

        if (!$provider instanceof DimensionProviderInterface) {
            $interfaceName = DimensionProviderInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $provider;
    }
}
