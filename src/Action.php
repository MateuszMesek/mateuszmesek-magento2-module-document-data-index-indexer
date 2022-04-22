<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer;

use InvalidArgumentException;
use Magento\Framework\Indexer\ActionInterface;
use Magento\Framework\Indexer\DimensionalIndexerInterface;
use MateuszMesek\DocumentDataIndexIndexer\Action\DimensionProviderFactory;
use MateuszMesek\DocumentDataIndexIndexer\Action\ExecutorFactory;
use MateuszMesek\DocumentDataIndexIndexer\Action\Full;
use MateuszMesek\DocumentDataIndexIndexer\Action\Row;
use MateuszMesek\DocumentDataIndexIndexer\Action\Rows;

class Action implements ActionInterface
{
    private DimensionProviderFactory $dimensionProviderFactory;
    private ExecutorFactory $executorFactory;
    private DimensionalIndexerInterface $dimensionalIndexer;
    private string $documentName;

    public function __construct(
        DimensionProviderFactory $dimensionProviderFactory,
        ExecutorFactory $executorFactory,
        DimensionalIndexerInterface $dimensionalIndexer,
        array $data
    )
    {
        if (!isset($data['document_name'])) {
            throw new InvalidArgumentException('Document name was not specified');
        }

        $this->dimensionProviderFactory = $dimensionProviderFactory;
        $this->executorFactory = $executorFactory;
        $this->dimensionalIndexer = $dimensionalIndexer;
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
            $this->dimensionalIndexer
        );

        /* @noinspection VariableFunctionsUsageInspection */
        call_user_func([$executor, 'execute'], $arguments);
    }
}
