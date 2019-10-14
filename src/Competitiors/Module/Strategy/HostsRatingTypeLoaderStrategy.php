<?php

declare(strict_types=1);

namespace Example\Competitors\Module\Strategy;

use Example\Competitors\Module\DataLoader\HostsRatingTypeByHostIdsClientIdLoader;
use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Module\HostTypeContext;

class HostsRatingTypeLoaderStrategy extends AbstractHostTypeStrategy
{
    protected const ID = 'hosts_rating_type_loader';
    /**
     * @var HostsRatingTypeByHostIdsClientIdLoader
     */
    private $dataLoader;
    
    public function shouldProcess(): bool
    {
        return $this->context->is(HostTypeContext::FIELD__PROJECT_ID)
            && !empty($this->container->data[HostTypeContainer::FIELD__HOST_IDS]);
    }
    
    public function prepare(): void
    {
        $context = $this->getContext();
        
        $this->dataLoader = new HostsRatingTypeByHostIdsClientIdLoader();
        
        $this->dataLoader->configure(
            [
                HostsRatingTypeByHostIdsClientIdLoader::CONF_HOST_IDS  => $this->container->data[HostTypeContainer::FIELD__HOST_IDS],
                HostsRatingTypeByHostIdsClientIdLoader::CONF_CLIENT_ID => $context->get(
                    HostTypeContext::FIELD__CLIENT_ID
                ),
                HostsRatingTypeByHostIdsClientIdLoader::CONF_FAVORITE  => $context->get(
                    HostTypeContext::FIELD__FILTER_FAVORITE
                ),
            ]
        );
        
        $this->dataLoader->prepare();
    }
    
    public function run(): void
    {
        $this->container->data[HostTypeContainer::FIELD__HOSTS_RATING_TYPE_LOAD] = $this->dataLoader->getData();
    }
}