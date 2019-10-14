<?php

declare(strict_types=1);

namespace Example\Competitors\Module\DataLoader;

use Example\Core\Components\Modular\AbstractDataLoaderPrototype;

class ProjectsTypeByHostIdsLoader extends AbstractDataLoaderPrototype
{
    public const CONF_HOST_IDS = 'hostIds';
    public const CONF_TYPE_ID  = 'typeId';
    /**
     * @var \CDbCommand
     */
    private $dataQuery;
    private $dataHosts;
    private $config = [
        self::CONF_HOST_IDS => null,
    ];
    
    public function configure(array $config = []): void
    {
        if (empty($config[self::CONF_HOST_IDS])) {
            throw new \InvalidArgumentException('Parametrs hostIds not empty');
        }
        
        $this->config = \array_merge($this->config, $config);
    }
    
    public function prepare(): void
    {
        $this->dataQuery = \Y::dbCmd('', \HostRatio::DATABASE_CONNECTOR_NAME)
            ->select(
                [
                    \Host::FIELD__ID,
                    \Host::FIELD__PROJECT_ID,
                ]
            )
            ->from(\Host::TABLE_NAME)
            ->where(
                [
                    'IN',
                    \Host::FIELD__ID,
                    $this->config[self::CONF_HOST_IDS],
                ]
            )
            ->andWhere('project_id > 0');
    }
    
    protected function loadData(): void
    {
        $this->dataHosts = $this->dataQuery->queryAll();
        
        if (empty($this->dataHosts)) {
            return;
        }
        
        $this->dataHosts = \array_combine(
            array_column($this->dataHosts, \Host::FIELD__PROJECT_ID),
            $this->dataHosts
        );
        
        $projectData = $this->projectType();
        
        foreach ($projectData as $data) {
            $projectId = (int) $data[\ProjectCompetitionConfig::FIELD__PROJECT_ID];
            $hostId    = (int) $this->dataHosts[$projectId][\Host::FIELD__ID];
            
            $this->data[$hostId] = [
                \HostRatio::FIELD__HOST_TO_ID              => $hostId,
                \HostRatio::FIELD__PROJECT_ID              => $projectId,
                \HostRatingType::FIELD__COMPETITOR_TYPE_ID => (int) $data[\ProjectCompetitionConfig::FIELD__COMPETITOR_TYPE_ID],
            ];
        }
    }
    
    private function projectType(): array
    {
        $projectQuery = \Y::dbCmd('')
            ->select(
                [
                    \ProjectCompetitionConfig::FIELD__PROJECT_ID,
                    \ProjectCompetitionConfig::FIELD__COMPETITOR_TYPE_ID,
                ]
            )
            ->from(\ProjectCompetitionConfig::TABLE_NAME . ' t')
            ->join(\Project::TABLE_NAME . ' p', 'p.id = t.project_id AND p.active = 1')
            ->where(
                [
                    'IN',
                    \ProjectCompetitionConfig::FIELD__PROJECT_ID,
                    array_keys($this->dataHosts),
                ]
            );
        
        if (!empty($this->config[self::CONF_TYPE_ID])) {
            $projectQuery->andWhere(
                sprintf(
                    '%s = %d',
                    \ProjectCompetitionConfig::FIELD__COMPETITOR_TYPE_ID,
                    $this->config[self::CONF_TYPE_ID]
                )
            );
        }
        
        return $projectQuery->queryAll();
    }
    
    protected function loadCursor(): void
    {
    }
}