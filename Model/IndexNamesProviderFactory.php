<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IndexNamesProviderInterface;

class IndexNamesProviderFactory
{
    public function __construct(
        private readonly Config                 $config,
        private readonly ObjectManagerInterface $objectManager,
        private readonly array                  $arguments = []
    )
    {
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

        $indexesNameProvider = $this->objectManager->create(
            $type,
            $this->arguments
        );

        if (!$indexesNameProvider instanceof IndexNamesProviderInterface) {
            $interfaceName = IndexNamesProviderInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $indexesNameProvider;
    }
}
