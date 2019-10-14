<?php

declare(strict_types=1);

namespace Example\Competitors\Module\DataLoader;

use Example\Core\Components\Modular\AbstractDataLoaderPrototype;

class CompetitiorTypeLoader extends AbstractDataLoaderPrototype
{
    /**
     * @var \CDbCommand
     */
    private $dataQuery;
    
    public function configure(array $config = []): void
    {
    }
    
    public function prepare(): void
    {
        $this->dataQuery = \Y::dbCmd('', \HostRatio::DATABASE_CONNECTOR_NAME)
            ->select(
                [
                    \CompetitorType::FIELD__ID,
                    \CompetitorType::FIELD__NAME,
                ]
            )
            ->from(\CompetitorType::TABLE_NAME);
    }
    
    protected function loadData(): void
    {
        $this->data = $this->dataQuery->queryAll();
    }
    
    protected function loadCursor(): void
    {
        $this->cursor = $this->dataQuery->query();
    }
}