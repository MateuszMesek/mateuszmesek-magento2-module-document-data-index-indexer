<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\Plugin\InjectIndexers;

use Magento\Framework\Indexer\Config\Reader;
use MateuszMesek\DocumentDataIndexIndexer\Model\Config;

class OnIndexerConfigReader
{
    public function __construct(
        private readonly Config $config
    )
    {
    }

    public function afterRead(
        Reader $reader,
        array  $output,
               $scope = null
    ): array
    {
        $documentNames = $this->config->getDocumentNames();

        foreach ($documentNames as $documentName) {
            $indexerId = "document_data_$documentName";

            $output[$indexerId] = [
                'indexer_id' => $indexerId,
                'view_id' => $indexerId,
                'action_class' => $this->config->getAction($documentName),
                'shared_index' => null,
                'title' => 'Document Data: ' . $this->convertNameToTile($documentName),
                'description' => '',
                'dependencies' => [],
                'document_name' => $documentName
            ];
        }

        return $output;
    }

    private function convertNameToTile(string $name): string
    {
        return ucwords(implode(' ', explode('_', $name)));
    }
}
