<?php

declare(strict_types=1);

namespace Example\Competitors\Module\DataLoader;

use Example\Core\Components\Modular\AbstractDataLoaderPrototype;

class HostsRatingTypeByHostIdsClientIdLoader extends AbstractDataLoaderPrototype
{
    public const CONF_HOST_IDS        = 'hostIds';
    public const CONF_CLIENT_ID       = 'clientId';
    public const CONF_FAVORITE        = 'favorite';
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
        if (empty($config[self::CONF_HOST_IDS]) || empty($config[self::CONF_CLIENT_ID])) {
            return;
        }
        
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
                    'type.project_id',
                    'type.client_id',
                    'type.favorite',
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
            ->andWhere('type.client_id = :clientId', [':clientId' => $this->config[self::CONF_CLIENT_ID]])
            ->order('t.host_to_id');
        
        if (!empty($this->config[self::CONF_FAVORITE])) {
            $this->dataQuery->andWhere(
                \sprintf(
                    'type.favorite IS %s',
                    (bool) $this->config[self::CONF_FAVORITE] ? 'TRUE' : 'FALSE'
                )
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