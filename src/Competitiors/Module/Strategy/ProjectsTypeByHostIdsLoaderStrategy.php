<?php

declare(strict_types=1);

namespace Example\Competitors\Module\Strategy;

use Example\Competitors\Module\DataLoader\ProjectsTypeByHostIdsLoader;
use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Module\HostTypeContext;

class ProjectsTypeByHostIdsLoaderStrategy extends AbstractHostTypeStrategy
{
    protected const ID = 'projects_type_by_hosts';
    /**
     * @var ProjectsTypeByHostIdsLoader
     */
    private $dataLoader;
    
    public function shouldProcess(): bool
    {
        return !empty($this->container->data[HostTypeContainer::FIELD__HOST_IDS]);
    }
    
    public function prepare(): void
    {
        $this->dataLoader = new ProjectsTypeByHostIdsLoader();
        
        $this->dataLoader->configure(
            [
                ProjectsTypeByHostIdsLoader::CONF_HOST_IDS => $this->container->data[HostTypeContainer::FIELD__HOST_IDS],
                ProjectsTypeByHostIdsLoader::CONF_TYPE_ID  => $this->context->get(
                    HostTypeContext::FIELD__FILTER_TYPE_ID
                ),
            ]
        );
        
        $this->dataLoader->prepare();
    }
    
    public function run(): void
    {
        $this->container->data[HostTypeContainer::FIELD__HOSTS_PROJECT_TYPE_LOAD] = $this->dataLoader->getData();
    }
}