<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use Magento\Framework\Config\DataInterface;
use MateuszMesek\DocumentDataApi\Model\Config\DocumentNamesInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\DataResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\DimensionProviderInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\EntityIdsResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\IndexIdsResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\IndexNameResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\IndexStructureBuilderInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\ReadHandlerInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\Config\SaveHandlerInterface;

class Config implements
    DocumentNamesInterface,
    DimensionProviderInterface,
    DataResolverInterface,
    ReadHandlerInterface,
    SaveHandlerInterface,
    IndexNameResolverInterface,
    IndexStructureBuilderInterface,
    IndexIdsResolverInterface,
    EntityIdsResolverInterface
{
    public function __construct(
        private readonly DataInterface $data
    )
    {
    }

    public function getDocumentNames(): array
    {
        $documents = $this->data->get();

        return array_keys($documents);
    }

    public function getAction(string $documentName): string
    {
        return $this->data->get("$documentName/action") ?: Action::class;
    }

    public function getDimensionProvider(string $documentName): ?string
    {
        return $this->data->get("$documentName/dimensionProvider");
    }

    public function getEntityIdsResolver(string $documentName): ?string
    {
        return $this->data->get("$documentName/entityIdsResolver");
    }

    public function getIndexNamesProvider(string $documentName): ?string
    {
        return $this->data->get("$documentName/indexNamesProvider");
    }

    public function getIndexNameResolver(string $documentName): ?string
    {
        return $this->data->get("$documentName/indexNameResolver");
    }

    public function getIndexStructureBuilder(string $documentName): ?string
    {
        return $this->data->get("$documentName/indexStructureBuilder");
    }

    public function getIndexIdsResolver(string $documentName): ?string
    {
        return $this->data->get("$documentName/indexIdsResolver");
    }

    public function getDataResolver(string $documentName): ?string
    {
        return $this->data->get("$documentName/dataResolver");
    }

    public function getReadHandler(string $documentName): ?string
    {
        return $this->data->get("$documentName/readHandler");
    }

    public function getSaveHandler(string $documentName): ?string
    {
        return $this->data->get("$documentName/saveHandler");
    }
}
