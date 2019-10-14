<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Module\DataLoader;

use Example\Competitors\Module\DataLoader\ProjectsTypeByHostIdsLoader;
use Example\Core\Components\Modular\AbstractDataLoaderPrototype;

class ProjectsTypeByHostIdsLoaderTest extends AbstractHostsRatingLoaderTest
{
    protected function createLoader(): AbstractDataLoaderPrototype
    {
        return new ProjectsTypeByHostIdsLoader();
    }
    
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Parametrs hostIds not empty
     */
    public function testPrepareEmptyHostRatioIds(): void
    {
        $this->loader->configure();
        $this->loader->prepare();
        
        $this->loader->getData();
    }
    
    public function dataProvider(): array
    {
        return [
            [
                0,
                [
                    1,
                    2,
                    3,
                    4,
                    5,
                ],
                [
                    1 => [
                        \HostRatio::FIELD__HOST_TO_ID              => 1,
                        \HostRatio::FIELD__PROJECT_ID              => 1,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 2,
                    ],
                    3 => [
                        \HostRatio::FIELD__HOST_TO_ID              => 3,
                        \HostRatio::FIELD__PROJECT_ID              => 3,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                    ],
                ],
            ],
        ];
    }
}