<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Action;

use Magento\Framework\Indexer\DimensionalIndexerInterface;
use Magento\Framework\Indexer\DimensionProviderInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\EntityIdsResolverInterface;

class Full implements ExecutorInterface
{
    public const TYPE = 'full';

    private DimensionProviderInterface $dimensionProvider;
    private EntityIdsResolverInterface $entityIdsResolver;
    private DimensionalIndexerInterface $dimensionalIndexer;

    public function __construct(
        DimensionProviderInterface $dimensionProvider,
        EntityIdsResolverInterface $entityIdsResolver,
        DimensionalIndexerInterface $dimensionalIndexer
    )
    {
        $this->dimensionProvider = $dimensionProvider;
        $this->entityIdsResolver = $entityIdsResolver;
        $this->dimensionalIndexer = $dimensionalIndexer;
    }

    public function execute(): void
    {
        foreach ($this->dimensionProvider->getIterator() as $dimensions) {
            $ids = $this->entityIdsResolver->resolve($dimensions);

            $this->dimensionalIndexer->executeByDimensions($dimensions, $ids);

            // TODO: remove not indexed ids
        }
    }
}
