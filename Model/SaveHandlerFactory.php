<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\SaveHandlerInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\SaveHandlerInterface;

class SaveHandlerFactory
{
    /**
     * @var SaveHandlerInterface[]
     */
    private array $instances = [];

    public function __construct(
        private readonly ConfigInterface        $config,
        private readonly ObjectManagerInterface $objectManager,
        private readonly array                  $arguments = []
    )
    {
    }

    public function create(string $documentName): SaveHandlerInterface
    {
        $type = $this->config->getSaveHandler($documentName);

        if (null === $type) {
            throw new InvalidArgumentException(
                "Save handler for '$documentName' document data is not configured"
            );
        }

        $saveHandler = $this->objectManager->create(
            $type,
            $this->arguments
        );

        if (!$saveHandler instanceof SaveHandlerInterface) {
            $interfaceName = SaveHandlerInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $saveHandler;
    }

    public function get(string $documentName): SaveHandlerInterface
    {
        if (!isset($this->instances[$documentName])) {
            $this->instances[$documentName] = $this->create($documentName);
        }

        return $this->instances[$documentName];
    }
}
