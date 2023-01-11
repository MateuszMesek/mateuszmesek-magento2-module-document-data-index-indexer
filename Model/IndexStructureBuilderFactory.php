<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\IndexStructureBuilderInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IndexStructureBuilderInterface;

class IndexStructureBuilderFactory
{
    /**
     * @var IndexStructureBuilderInterface[]
     */
    private array $instances = [];

    public function __construct(
        private readonly ConfigInterface        $config,
        private readonly ObjectManagerInterface $objectManager,
        private readonly array                  $arguments = []
    )
    {
    }

    public function create(string $documentName): IndexStructureBuilderInterface
    {
        $type = $this->config->getIndexStructureBuilder($documentName);

        if (null === $type) {
            throw new InvalidArgumentException(
                "Index structure builder for '$documentName' document data is not configured"
            );
        }

        $indexStructureBuilder = $this->objectManager->create(
            $type,
            $this->arguments
        );

        if (!$indexStructureBuilder instanceof IndexStructureBuilderInterface) {
            $interfaceName = IndexStructureBuilderInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $indexStructureBuilder;
    }

    public function get(string $documentName): IndexStructureBuilderInterface
    {
        if (!isset($this->instances[$documentName])) {
            $this->instances[$documentName] = $this->create($documentName);
        }

        return $this->instances[$documentName];
    }
}
