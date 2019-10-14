<?php

declare(strict_types=1);

namespace Example\Competitors\Module\DataLoader;

use Example\Core\Components\Modular\AbstractDataLoaderPrototype;

class HostsRatingTypeByHostIdsNotClientIdLoader extends AbstractDataLoaderPrototype
{
    public const CONF_HOST_IDS  = 'hostIds';
    public const CONF_CLIENT_ID = 'clientId';
    public const CONF_TYPE_ID   = 'typeId';
    /**
     * @var \CDbCommand
     */
    private $dataQuery;
    private $config = [
        self::CONF_HOST_IDS  => null,
        self::CONF_CLIENT_ID => null,
    ];
    
    public function configure(array $config = []): void
    {
        $this->config = \array_merge($this->config, $config);
    }
    
    public function prepare(): void
    {
        $this->dataQuery = \Y::dbCmd('', \HostRatio::DATABASE_CONNECTOR_NAME)
            ->select(
                [
                    't.host_from_id',
                    't.host_to_id',
                    'type.competitor_type_id',
                    'type.client_id',
                ]
            )
            ->from(\HostRatio::TABLE_NAME . ' t')
            ->join(\HostRatingType::TABLE_NAME . ' type', 'type.host_ratio_id = t.id')
            ->where(
                [
                    'IN',
                    \HostRatio::FIELD__HOST_TO_ID,
                    $this->config[self::CONF_HOST_IDS],
                ]
            )
            ->andWhere('type.client_id != :clientId', [':clientId' => $this->config[self::CONF_CLIENT_ID]]);
        
        if (!empty($this->config[self::CONF_TYPE_ID])) {
            $this->dataQuery->andWhere(
                'type.competitor_type_id = :typeId',
                [':typeId' => $this->config[self::CONF_TYPE_ID]]
            );
        }
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