<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Action;

use Magento\Framework\Indexer\DimensionProviderInterface;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexer\DimensionProvider\WithDocumentNameProvider;
use MateuszMesek\DocumentDataIndexIndexer\DimensionProviderFactory as DocumentDimensionProviderFactory;

class DimensionProviderFactory
{
    private DocumentDimensionProviderFactory $dimensionProviderFactory;
    private ObjectManagerInterface $objectManager;

    public function __construct(
        DocumentDimensionProviderFactory $dimensionProviderFactory,
        ObjectManagerInterface           $objectManager
    )
    {
        $this->dimensionProviderFactory = $dimensionProviderFactory;
        $this->objectManager = $objectManager;
    }

    public function create(string $documentName): DimensionProviderInterface
    {
        $dimensionProvider = $this->dimensionProviderFactory->create($documentName);

        return $this->objectManager->create(
            WithDocumentNameProvider::class,
            [
                'documentName' => $documentName,
                'dimensionProvider' => $dimensionProvider
            ]
        );
    }
}
