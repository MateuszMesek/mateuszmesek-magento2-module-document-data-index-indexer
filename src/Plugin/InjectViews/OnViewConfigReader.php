<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Plugin\InjectViews;

use Magento\Framework\Indexer\Action\Dummy as Action;
use Magento\Framework\Mview\Config\Reader;
use MateuszMesek\DocumentDataIndexIndexer\Config;

class OnViewConfigReader
{
    private Config $config;

    public function __construct(
        Config $config
    )
    {
        $this->config = $config;
    }

    public function afterRead(
        Reader $reader,
        array  $output,
               $scope = null
    ): array
    {
        $documentNames = $this->config->getDocumentNames();

        foreach ($documentNames as $documentName) {
            $viewId = "document_data_$documentName";

            $output[$viewId] = [
                'view_id' => $viewId,
                'action_class' => Action::class,
                'group' => 'indexer',
                'subscriptions' => [],
            ];
        }

        return $output;
    }
}
