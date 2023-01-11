<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\IndexNameResolverInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IndexNameResolverInterface;

class IndexNameResolverFactory
{
    /**
     * @var IndexNameResolverInterface[]
     */
    private array $instances = [];

    public function __construct(
        private readonly ConfigInterface        $config,
        private readonly ObjectManagerInterface $objectManager,
        private readonly array                  $arguments = []
    )
    {
    }

    public function create(string $documentName): IndexNameResolverInterface
    {
        $type = $this->config->getIndexNameResolver($documentName);

        if (null === $type) {
            throw new InvalidArgumentException(
                "Index name resolver for '$documentName' document data is not configured"
            );
        }

        $indexNameResolver = $this->objectManager->create(
            $type,
            $this->arguments
        );

        if (!$indexNameResolver instanceof IndexNameResolverInterface) {
            $interfaceName = IndexNameResolverInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $indexNameResolver;
    }

    public function get(string $documentName): IndexNameResolverInterface
    {
        if (!isset($this->instances[$documentName])) {
            $this->instances[$documentName] = $this->create($documentName);
        }

        return $this->instances[$documentName];
    }
}
