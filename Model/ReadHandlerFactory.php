<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\ReadHandlerInterface as ConfigInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\ReadHandlerInterface;

class ReadHandlerFactory
{
    /**
     * @var ReadHandlerInterface[]
     */
    private array $instances = [];

    public function __construct(
        private readonly ConfigInterface        $config,
        private readonly ObjectManagerInterface $objectManager,
        private readonly array                  $arguments = []
    )
    {
    }

    public function create(string $documentName): ReadHandlerInterface
    {
        $type = $this->config->getReadHandler($documentName);

        if (null === $type) {
            throw new InvalidArgumentException(
                "Read handler for '$documentName' document data is not configured"
            );
        }

        $readHandler = $this->objectManager->create(
            $type,
            $this->arguments
        );

        if (!$readHandler instanceof ReadHandlerInterface) {
            $interfaceName = ReadHandlerInterface::class;

            throw new InvalidArgumentException(
                "$type doesn't implement $interfaceName"
            );
        }

        return $readHandler;
    }

    public function get(string $documentName): ReadHandlerInterface
    {
        if (!isset($this->instances[$documentName])) {
            $this->instances[$documentName] = $this->create($documentName);
        }

        return $this->instances[$documentName];
    }
}
