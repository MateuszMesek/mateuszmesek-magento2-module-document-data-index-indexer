<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\IndexNamesProviderInterface;

class IndexNamesProviderFactory
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

    public function create(string $documentName): IndexNamesProviderInterface
    {
        $type = $this->config->getIndexNamesProvider($documentName);

        if (null === $type) {
            return $this->objectManager->create(
                IndexNamesProvider::class,
                [
                    'documentName' => $documentName
                ]
            );
        }

        $indexesNameProvider = $this->objectManager->create($type);

        if (!$indexesNameProvider instanceof IndexNamesProviderInterface) {
            $interfaceName = IndexNamesProviderInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $indexesNameProvider;
    }
}
