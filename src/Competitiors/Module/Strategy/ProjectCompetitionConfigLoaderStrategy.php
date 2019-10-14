<?php

declare(strict_types=1);

namespace Example\Competitors\Module\Strategy;

use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Module\HostTypeContext;
use Example\Project\Module\DataLoader\ProjectCompetitionConfigByProjectIdLoader;

class ProjectCompetitionConfigLoaderStrategy extends AbstractHostTypeStrategy
{
    protected const ID = 'project_competition_config_loader';
    /**
     * @var ProjectCompetitionConfigByProjectIdLoader
     */
    private $dataLoader;
    
    public function shouldProcess(): bool
    {
        return $this->context->is(HostTypeContext::FIELD__PROJECT_ID);
    }
    
    public function prepare(): void
    {
        $context = $this->getContext();
        
        $this->dataLoader = new ProjectCompetitionConfigByProjectIdLoader();
        
        $this->dataLoader->configure(
            [
                ProjectCompetitionConfigByProjectIdLoader::CONF_PROJECT_ID => $context->get(
                    HostTypeContext::FIELD__PROJECT_ID
                ),
            ]
        );
        
        $this->dataLoader->prepare();
    }
    
    public function run(): void
    {
        $typeId = null;
        $data   = $this->dataLoader->getData();
        if (!empty($data)) {
            $typeId = $data[\ProjectCompetitionConfig::FIELD__COMPETITOR_TYPE_ID];
        }
        
        $this->container->data[HostTypeContainer::FIELD__PROJECT_COMPETITION_TYPE_ID] = $typeId;
    }
}