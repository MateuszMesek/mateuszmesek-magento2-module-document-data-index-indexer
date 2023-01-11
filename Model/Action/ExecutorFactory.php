<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\Action;

use InvalidArgumentException;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use Magento\Framework\Indexer\DimensionProviderInterface;
use Magento\Framework\ObjectManager\TMapFactory;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IdsResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IndexStructureBuilderInterface;

class ExecutorFactory
{
    public function __construct(
        private readonly TMapFactory $TMapFactory,
        private readonly array       $types = []
    )
    {
    }

    public function create(
        string                         $type,
        DimensionProviderInterface     $dimensionProvider,
        IndexStructureBuilderInterface $indexStructureBuilder,
        IdsResolverInterface           $idsResolver,
        DimensionalIndexerInterface    $dimensionalIndexer
    ): ExecutorInterface
    {
        $executor = $this->TMapFactory->create([
            'type' => ExecutorInterface::class,
            'array' => $this->types,
            'objectCreationStrategy' => static function (ObjectManagerInterface $objectManager, string $objectName) use ($dimensionProvider, $indexStructureBuilder, $idsResolver, $dimensionalIndexer) {
                return $objectManager->create(
                    $objectName,
                    [
                        'dimensionProvider' => $dimensionProvider,
                        'indexStructureBuilder' => $indexStructureBuilder,
                        'idsResolver' => $idsResolver,
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
