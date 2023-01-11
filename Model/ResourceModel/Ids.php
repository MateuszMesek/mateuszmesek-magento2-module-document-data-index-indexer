<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Ids extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(
            'document_data_ids_pattern',
            'id'
        );
    }

    public function getDocumentDataTable(string $documentName): string
    {
        return $this->getTable("document_data_{$documentName}_ids");
    }
}
