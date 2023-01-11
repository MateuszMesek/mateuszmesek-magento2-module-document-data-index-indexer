<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model;

use InvalidArgumentException;
use Magento\Framework\Indexer\ActionInterface;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use MateuszMesek\DocumentDataIndexIndexer\Model\Action\DimensionProviderFactory;
use MateuszMesek\DocumentDataIndexIndexer\Model\Action\ExecutorFactory;
use MateuszMesek\DocumentDataIndexIndexer\Model\Action\Full;
use MateuszMesek\DocumentDataIndexIndexer\Model\Action\Row;
use MateuszMesek\DocumentDataIndexIndexer\Model\Action\Rows;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IdsResolverInterface;
use MateuszMesek\DocumentDataIndexIndexerApi\Model\IndexStructureBuilderInterface;

class Action implements ActionInterface
{
    private string $documentName;

    public function __construct(
        private readonly DimensionProviderFactory       $dimensionProviderFactory,
        private readonly ExecutorFactory                $executorFactory,
        private readonly IndexStructureBuilderInterface $indexStructureBuilder,
        private readonly IdsResolverInterface           $idsResolver,
        private readonly DimensionalIndexerInterface    $dimensionalIndexer,
        array                                           $data
    )
    {
        if (!isset($data['document_name'])) {
            throw new InvalidArgumentException('Document name was not specified');
        }

        $this->documentName = $data['document_name'];
    }

    public function executeFull(): void
    {
        $this->executeAction(Full::TYPE);
    }

    public function executeList(array $ids): void
    {
        $this->executeAction(Rows::TYPE, $ids);
    }

    public function executeRow($id): void
    {
        $this->executeAction(Row::TYPE, $id);
    }

    private function executeAction(string $actionType, $arguments = null): void
    {
        $dimensionProvider = $this->dimensionProviderFactory->create(
            $this->documentName
        );

        $executor = $this->executorFactory->create(
            $actionType,
            $dimensionProvider,
            $this->indexStructureBuilder,
            $this->idsResolver,
            $this->dimensionalIndexer
        );

        /* @noinspection VariableFunctionsUsageInspection */
        call_user_func([$executor, 'execute'], $arguments);
    }
}
