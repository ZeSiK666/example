<?php
declare(strict_types=1);

namespace Example\Competitors\Module;

use Example\AnalyticsTop100\Module\CompetitionContainer;
use Example\Core\Components\Modular\AbstractDataPresenterPrototype;

class HostTypePresenter extends AbstractDataPresenterPrototype
{
    public function formatResult(): array
    {
        $projectCompetitiorTypeName = '';
        
        $projectCompetitiorTypeId = $this->container->data[HostTypeContainer::FIELD__PROJECT_COMPETITION_TYPE_ID];
        $competitiorTypeData      = $this->container->data[HostTypeContainer::FIELD__COMPETITOR_TYPE_LOAD];
        $competitorHosts          = $this->container->result[CompetitionContainer::RESULT__COMPETITOR_HOSTS] ?? [];
        $hostsTypedata            = $this->container->result[HostTypeContainer::FIELD__RESULT_HOSTS_TYPE_DATA] ?? [];
        
        if (!empty($competitiorTypeData[$projectCompetitiorTypeId])) {
            $projectCompetitiorTypeName = $competitiorTypeData[$projectCompetitiorTypeId];
        }
        
        return [
            HostTypeContainer::PRESENTER__HOSTS                         => $this->container->data[CompetitionContainer::FIELD__HOSTS] ?? [],
            HostTypeContainer::PRESENTER__PROJECT_COMPETITION_TYPE_ID   => $projectCompetitiorTypeId,
            HostTypeContainer::PRESENTER__PROJECT_COMPETITION_TYPE_NAME => $projectCompetitiorTypeName,
            HostTypeContainer::PRESENTER__COMPETITOR_TYPES              => $this->container->data[HostTypeContainer::FIELD__COMPETITOR_TYPE_LOAD] ?? [],
            HostTypeContainer::PRESENTER__HOSTS_TYPE_DATA               => $hostsTypedata,
            HostTypeContainer::PRESENTER__COMPETITOR_PROXY_HOST_IDS     => array_column(
                $competitorHosts,
                \Host::FIELD__ID,
                \Host::FIELD__PROXY_HOST_ID
            ),
            HostTypeContainer::PRESENTER__FILTERED_COMPETITOR_HOSTS_IDS => array_column(
                array_intersect_key(
                    $competitorHosts,
                    $hostsTypedata
                ),
                \Host::FIELD__PROXY_HOST_ID
            ),
        ];
    }
}