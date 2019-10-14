<?php

declare(strict_types=1);

namespace Example\Competitors\Module\Strategy;

use Example\AnalyticsTop100\Module\CompetitionContainer;
use Example\Competitors\CompetitorsDirectionConstants;
use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Module\HostTypeContext;
use Example\Competitors\Regulations\CompetitorRegulationConstants;

class FiltrationStrategy extends AbstractHostTypeStrategy
{
    private $projectTypeId;
    private $equal;
    private $typeId;
    private $mainHostId;
    
    public function shouldProcess(): bool
    {
        return $this->context->get(HostTypeContext::FIELD__FILTER_EQUAL)
            || $this->context->get(HostTypeContext::FIELD__FILTER_TYPE_ID);
    }
    
    public function prepare(): void
    {
        $this->projectTypeId = $this->container->data[HostTypeContainer::FIELD__PROJECT_COMPETITION_TYPE_ID] ?? null;
        $this->equal         = $this->context->get(HostTypeContext::FIELD__FILTER_EQUAL);
        $this->typeId        = $this->context->get(HostTypeContext::FIELD__FILTER_TYPE_ID);
        $this->mainHostId    = $this->container->data[CompetitionContainer::FIELD__PROJECT_HOST_ID] ?? null;
    }
    
    public function run(): void
    {
        foreach ($this->container->result[HostTypeContainer::FIELD__RESULT_HOSTS_TYPE_DATA] as $id => $data) {
            if (!$this->filter($data)) {
                unset($this->container->result[HostTypeContainer::FIELD__RESULT_HOSTS_TYPE_DATA][$id]);
            }
        }
    }
    
    private function filter(array $data): bool
    {
        if ($this->equal) {
            return $this->filterEqual($data);
        }
        
        if ($this->typeId) {
            return $this->filterType($data);
        }
        
        return false;
    }
    
    private function filterEqual(array $data): bool
    {
        if ($this->mainHostId === $data[CompetitorRegulationConstants::FIELD__HOST_ID]) {
            return true;
        }
        
        $isEqual = $data[CompetitorRegulationConstants::FIELD__EQUAL] ?? false;
        switch ($this->equal) {
            case CompetitorsDirectionConstants::COMPETITOR_TYPE_STRAIGHT_ID:
                return $this->projectTypeId && $isEqual;
            
            case CompetitorsDirectionConstants::COMPETITOR_TYPE_COMMON_ID:
                return !$isEqual;
            
            default:
                return false;
        }
    }
    
    private function filterType(array $data): bool
    {
        return $this->typeId === $data[CompetitorRegulationConstants::FIELD__TYPE_ID];
    }
}