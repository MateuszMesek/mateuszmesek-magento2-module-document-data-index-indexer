<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\IndexIdsResolverInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IdsResolverInterface;

class IndexIdsResolverFactory
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
        $type = $this->config->getIndexIdsResolver($documentName);

        if (null === $type) {
            throw new InvalidArgumentException(
                "Index ids resolver for '$documentName' document data is not configured"
            );
        }

        $indexIdsResolver = $this->objectManager->create(
            $type,
            $this->arguments
        );

        if (!$indexIdsResolver instanceof IdsResolverInterface) {
            $interfaceName = IdsResolverInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $indexIdsResolver;
    }
}
