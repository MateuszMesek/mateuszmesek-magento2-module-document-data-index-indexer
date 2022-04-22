<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Action;

use InvalidArgumentException;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use Magento\Framework\Indexer\DimensionProviderInterface;
use Magento\Framework\ObjectManager\TMapFactory;
use Magento\Framework\ObjectManagerInterface;

class ExecutorFactory
{
    private TMapFactory $TMapFactory;
    private array $types;

    public function __construct(
        TMapFactory $TMapFactory,
        array $types = []
    )
    {
        $this->TMapFactory = $TMapFactory;
        $this->types = $types;
    }

    public function create(
        string $type,
        DimensionProviderInterface $dimensionProvider,
        DimensionalIndexerInterface $dimensionalIndexer
    ): ExecutorInterface
    {
        $executor = $this->TMapFactory->create([
            'type' => ExecutorInterface::class,
            'array' => $this->types,
            'objectCreationStrategy' => static function (ObjectManagerInterface $objectManager, string $objectName) use ($dimensionProvider, $dimensionalIndexer) {
                return $objectManager->create(
                    $objectName,
                    [
                        'dimensionProvider' => $dimensionProvider,
                        'dimensionalIndexer' => $dimensionalIndexer
                    ]
                );
            }
        ])->offsetGet($type);

        if (!$executor) {
            throw new InvalidArgumentException("Executor of '$type' action is not defined");
        }

        return $executor;
    }
}
