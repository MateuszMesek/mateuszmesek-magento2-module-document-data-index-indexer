<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\DataResolverInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\DataResolverInterface;

class DataResolverFactory
{
    public function __construct(
        private readonly ConfigInterface        $config,
        private readonly ObjectManagerInterface $objectManager,
        private readonly array                  $arguments = []
    )
    {
    }

    public function create(string $documentName): DataResolverInterface
    {
        $type = $this->config->getDataResolver($documentName);

        if (null === $type) {
            throw new InvalidArgumentException(
                "Data resolver for '$documentName' document data is not configured"
            );
        }

        $dataResolver = $this->objectManager->create(
            $type,
            $this->arguments
        );

        if (!$dataResolver instanceof DataResolverInterface) {
            $interfaceName = DataResolverInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $dataResolver;
    }
}
