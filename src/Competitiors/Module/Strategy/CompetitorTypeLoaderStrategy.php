<?php

declare(strict_types=1);

namespace Example\Competitors\Module\Strategy;

use Example\Competitors\Module\DataLoader\CompetitiorTypeLoader;
use Example\Competitors\Module\HostTypeContainer;

class CompetitorTypeLoaderStrategy extends AbstractHostTypeStrategy
{
    protected const ID = 'competitor_type_loader';
    /**
     * @var CompetitiorTypeLoader
     */
    private $dataLoader;
    
    public function shouldProcess(): bool
    {
        return true;
    }
    
    public function prepare(): void
    {
        $this->dataLoader = new CompetitiorTypeLoader();
        
        $this->dataLoader->prepare();
    }
    
    public function run(): void
    {
        $this->container->data[HostTypeContainer::FIELD__COMPETITOR_TYPE_LOAD] = array_column(
            $this->dataLoader->getData(),
            \CompetitorType::FIELD__NAME,
            \CompetitorType::FIELD__ID
        );
    }
}