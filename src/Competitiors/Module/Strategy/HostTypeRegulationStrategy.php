<?php

declare(strict_types=1);

namespace Example\Competitors\Module\Strategy;

use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Module\HostTypeContext;
use Example\Competitors\Regulations\HostTypeRegulationPresenter;
use Example\Competitors\Regulations\HostTypeRegulationProcessor;
use Example\Competitors\Regulations\Strategy\ClientNotProjectRegulationStrategy;
use Example\Competitors\Regulations\Strategy\ClientProjectRegulationStrategy;
use Example\Competitors\Regulations\Strategy\NotClientNotProjectRegulationStrategy;
use Example\Competitors\Regulations\Strategy\ProjectsTypeRegulationStrategy;

class HostTypeRegulationStrategy extends AbstractHostTypeStrategy
{
    protected const ID = 'host_type_regulation';
    /**
     * @var HostTypeRegulationProcessor
     */
    private $regulationProcessor;
    
    public function shouldProcess(): bool
    {
        return $this->context->is(HostTypeContext::FIELD__CLIENT_ID)
            && $this->context->is(HostTypeContext::FIELD__PROJECT_ID);
    }
    
    public function prepare(): void
    {
        $this->regulationProcessor = new HostTypeRegulationProcessor();
        $this->regulationProcessor->setContext($this->context);
        $this->regulationProcessor->setContainer($this->container);
        
        $this->regulationProcessor->addStrategy(new ClientProjectRegulationStrategy());
        $this->regulationProcessor->addStrategy(new ClientNotProjectRegulationStrategy());
        $this->regulationProcessor->addStrategy(new HostsRatingTypeByHostIdsNotClientIdLoaderStrategy());
        $this->regulationProcessor->addStrategy(new NotClientNotProjectRegulationStrategy());
        $this->regulationProcessor->addStrategy(new ProjectsTypeRegulationStrategy());
        
        $this->regulationProcessor->setDataPresenter(new HostTypeRegulationPresenter());
        
        $this->regulationProcessor->prepare();
    }
    
    public function run(): void
    {
        $this->regulationProcessor->process();
        
        $this->container->result[HostTypeContainer::FIELD__RESULT_HOSTS_TYPE_DATA] = $this->regulationProcessor->getResult();
    }
}