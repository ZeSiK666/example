<?php

declare(strict_types=1);

namespace Example\Competitors\Module\Strategy;

use Example\Competitors\Module\DataLoader\HostsRatingTypeByHostIdsNotClientIdLoader;
use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Module\HostTypeContext;

class HostsRatingTypeByHostIdsNotClientIdLoaderStrategy extends AbstractHostTypeStrategy
{
    protected const ID = 'hosts_rating_type_not_clientloader';
    /**
     * @var HostsRatingTypeByHostIdsNotClientIdLoader
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
        
        $this->dataLoader = new HostsRatingTypeByHostIdsNotClientIdLoader();
        
        $hostRatingIds = array_column(
            $this->container->data[HostTypeContainer::FIELD__HOSTS_TYPE_DATA] ?? [],
            'host_id'
        );
        $hostIds       = array_diff($this->container->data[HostTypeContainer::FIELD__HOST_IDS], $hostRatingIds);
        
        $this->dataLoader->configure(
            [
                HostsRatingTypeByHostIdsNotClientIdLoader::CONF_HOST_IDS  => $hostIds,
                HostsRatingTypeByHostIdsNotClientIdLoader::CONF_CLIENT_ID => $context->get(
                    HostTypeContext::FIELD__CLIENT_ID
                ),
                HostsRatingTypeByHostIdsNotClientIdLoader::CONF_TYPE_ID   => $context->get(
                    HostTypeContext::FIELD__FILTER_TYPE_ID
                ),
            ]
        );
        
        $this->dataLoader->prepare();
    }
    
    public function run(): void
    {
        $this->container->data[HostTypeContainer::FIELD__HOSTS_RATING_TYPE_NOT_CLIENT_LOAD] = $this->dataLoader->getData(
        );
    }
}