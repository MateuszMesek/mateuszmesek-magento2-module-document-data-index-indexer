<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer;

use Magento\Framework\Config\DataInterface;
use MateuszMesek\DocumentDataIndexApi\Config\DataResolverInterface;
use MateuszMesek\DocumentDataIndexApi\Config\SaveHandlerInterface;

class Config implements DataResolverInterface, SaveHandlerInterface
{
    private DataInterface $data;

    public function __construct(
        DataInterface $data
    )
    {
        $this->data = $data;
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

    public function getDimensionProvider(string $documentName): string
    {
        return $this->data->get("$documentName/dimensionProvider");
    }

    public function getEntityIdsResolver(string $documentName): string
    {
        return $this->data->get("$documentName/entityIdsResolver");
    }

    public function getIndexNamesProvider(string $documentName): ?string
    {
        return $this->data->get("$documentName/indexNamesProvider");
    }

    public function getIndexNameResolver(string $documentName): string
    {
        return $this->data->get("$documentName/indexNameResolver");
    }

    public function getDataResolver(string $documentName): ?string
    {
        return $this->data->get("$documentName/dataResolver");
    }

    public function getSaveHandler(string $documentName): ?string
    {
        return $this->data->get("$documentName/saveHandler");
    }
}
