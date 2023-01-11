<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\Plugin\InjectViews;

use Magento\Framework\Indexer\Action\Dummy as Action;
use Magento\Framework\Mview\Config\Reader;
use MateuszMesek\DocumentDataIndexIndexer\Model\Config;

class OnViewConfigReader
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
