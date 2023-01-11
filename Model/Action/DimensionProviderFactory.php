<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\Action;

use Magento\Framework\Indexer\DimensionProviderInterface;
use Magento\Framework\ObjectManagerInterface;
use MateuszMesek\DocumentDataIndexIndexer\Model\DimensionProvider\WithDocumentNameProvider;
use MateuszMesek\DocumentDataIndexIndexer\Model\DimensionProviderFactory as DocumentDimensionProviderFactory;

class DimensionProviderFactory
{
    public function __construct(
        private readonly DocumentDimensionProviderFactory $dimensionProviderFactory,
        private readonly ObjectManagerInterface           $objectManager
    )
    {
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
