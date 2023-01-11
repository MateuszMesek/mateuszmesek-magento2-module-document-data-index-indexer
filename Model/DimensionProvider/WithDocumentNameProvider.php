<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\DimensionProvider;

use Magento\Framework\Indexer\DimensionProviderInterface;
use MateuszMesek\DocumentDataIndexIndexer\Model\Dimension\Factory;
use Traversable;

class WithDocumentNameProvider implements DimensionProviderInterface
{
    public const DIMENSION_NAME = 'document_name';

    public function __construct(
        private readonly string                     $documentName,
        private readonly DimensionProviderInterface $dimensionProvider,
        private readonly Factory                    $factory
    )
    {
    }

    public function getIterator(): Traversable
    {
        foreach ($this->dimensionProvider->getIterator() as $dimensions) {
            $dimension = $this->factory->create(self::DIMENSION_NAME, $this->documentName);

            $dimensions[$dimension->getName()] = $dimension;

            yield $dimensions;
        }
    }
}
