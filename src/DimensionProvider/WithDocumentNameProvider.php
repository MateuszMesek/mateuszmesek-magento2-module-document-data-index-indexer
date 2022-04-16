<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\DimensionProvider;

use Magento\Framework\Indexer\DimensionProviderInterface;
use Traversable;

class WithDocumentNameProvider implements DimensionProviderInterface
{
    public const DIMENSION_NAME = 'document_name';

    private string $documentName;
    private DimensionProviderInterface $dimensionProvider;
    private Factory $factory;

    public function __construct(
        string                     $documentName,
        DimensionProviderInterface $dimensionProvider,
        Factory           $factory
    )
    {
        $this->documentName = $documentName;
        $this->dimensionProvider = $dimensionProvider;
        $this->factory = $factory;
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
