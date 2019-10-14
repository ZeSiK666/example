<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Module\Strategy;

use Example\AnalyticsTop100\Module\CompetitionContainer;
use Example\Competitors\CompetitorsDirectionConstants;
use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Module\HostTypeContext;
use Example\Competitors\Module\Strategy\FiltrationStrategy;
use Example\Competitors\Regulations\CompetitorRegulationConstants;
use Example\Core\Components\Modular\AbstractStrategyPrototype;
use Example\Tests\Competitors\AbstractCompetitorsStrategyTest;

class FiltrationStrategyTest extends AbstractCompetitorsStrategyTest
{
    protected function createStrategy(): AbstractStrategyPrototype
    {
        return new FiltrationStrategy();
    }
    
    public function testRun(): void
    {
        self::assertFalse($this->strategy->shouldProcess());
    }
    
    /**
     * @param int   $typeId
     * @param array $data
     * @param array $expected
     *
     * @dataProvider dataProviderTypeId
     */
    public function testRunTypeId(int $typeId, array $data, array $expected): void
    {
        $this->context->set(HostTypeContext::FIELD__FILTER_TYPE_ID, $typeId);
        
        $this->container->result = $data;
        
        self::assertTrue($this->strategy->shouldProcess());
        $this->strategy->prepare();
        $this->strategy->run();
        
        self::assertSame($expected, $this->container->result[HostTypeContainer::FIELD__RESULT_HOSTS_TYPE_DATA]);
    }
    
    public function dataProviderTypeId(): array
    {
        return [
            [
                1,
                [
                    HostTypeContainer::FIELD__RESULT_HOSTS_TYPE_DATA => [
                        [
                            CompetitorRegulationConstants::FIELD__EQUAL   => 0,
                            CompetitorRegulationConstants::FIELD__TYPE_ID => 1,
                            CompetitorRegulationConstants::FIELD__HOST_ID => 1,
                        ],
                        [
                            CompetitorRegulationConstants::FIELD__EQUAL   => 1,
                            CompetitorRegulationConstants::FIELD__TYPE_ID => 2,
                            CompetitorRegulationConstants::FIELD__HOST_ID => 2,
                        ],
                        [
                            CompetitorRegulationConstants::FIELD__EQUAL   => 2,
                            CompetitorRegulationConstants::FIELD__TYPE_ID => 3,
                            CompetitorRegulationConstants::FIELD__HOST_ID => 3,
                        ],
                        [
                            CompetitorRegulationConstants::FIELD__EQUAL   => 3,
                            CompetitorRegulationConstants::FIELD__TYPE_ID => 4,
                            CompetitorRegulationConstants::FIELD__HOST_ID => 4,
                        ],
                    ],
                ],
                [
                    [
                        CompetitorRegulationConstants::FIELD__EQUAL   => 0,
                        CompetitorRegulationConstants::FIELD__TYPE_ID => 1,
                        CompetitorRegulationConstants::FIELD__HOST_ID => 1,
                    ],
                ],
            ],
        ];
    }
    
    /**
     * @param int   $equal
     * @param array $data
     * @param array $result
     * @param array $expected
     *
     * @dataProvider dataProviderEqual
     */
    public function testRunEqual(int $equal, array $data, array $result, array $expected): void
    {
        $this->context->set(HostTypeContext::FIELD__FILTER_EQUAL, $equal);
        $this->container->data   = $data;
        $this->container->result = $result;
        
        self::assertTrue($this->strategy->shouldProcess());
        $this->strategy->prepare();
        $this->strategy->run();
        
        self::assertSame($expected, $this->container->result[HostTypeContainer::FIELD__RESULT_HOSTS_TYPE_DATA]);
    }
    
    public function dataProviderEqual(): array
    {
        return [
            [
                CompetitorsDirectionConstants::COMPETITOR_TYPE_STRAIGHT_ID,
                [
                    HostTypeContainer::FIELD__PROJECT_COMPETITION_TYPE_ID => 1,
                    CompetitionContainer::FIELD__PROJECT_HOST_ID          => 1,
                ],
                [
                    HostTypeContainer::FIELD__RESULT_HOSTS_TYPE_DATA => [
                        [
                            CompetitorRegulationConstants::FIELD__EQUAL   => 0,
                            CompetitorRegulationConstants::FIELD__TYPE_ID => 1,
                            CompetitorRegulationConstants::FIELD__HOST_ID => 1,
                        ],
                        [
                            CompetitorRegulationConstants::FIELD__EQUAL   => 1,
                            CompetitorRegulationConstants::FIELD__TYPE_ID => 2,
                            CompetitorRegulationConstants::FIELD__HOST_ID => 2,
                        ],
                        [
                            CompetitorRegulationConstants::FIELD__EQUAL   => 0,
                            CompetitorRegulationConstants::FIELD__TYPE_ID => 3,
                            CompetitorRegulationConstants::FIELD__HOST_ID => 3,
                        ],
                    ],
                ],
                [
                    [
                        CompetitorRegulationConstants::FIELD__EQUAL   => 0,
                        CompetitorRegulationConstants::FIELD__TYPE_ID => 1,
                        CompetitorRegulationConstants::FIELD__HOST_ID => 1,
                    ],
                    [
                        CompetitorRegulationConstants::FIELD__EQUAL   => 1,
                        CompetitorRegulationConstants::FIELD__TYPE_ID => 2,
                        CompetitorRegulationConstants::FIELD__HOST_ID => 2,
                    ],
                ],
            ],
            [
                CompetitorsDirectionConstants::COMPETITOR_TYPE_COMMON_ID,
                [
                    HostTypeContainer::FIELD__PROJECT_COMPETITION_TYPE_ID => 2,
                ],
                [
                    HostTypeContainer::FIELD__RESULT_HOSTS_TYPE_DATA => [
                        [
                            CompetitorRegulationConstants::FIELD__EQUAL   => 0,
                            CompetitorRegulationConstants::FIELD__TYPE_ID => 1,
                            CompetitorRegulationConstants::FIELD__HOST_ID => 1,
                        ],
                        [
                            CompetitorRegulationConstants::FIELD__EQUAL   => 1,
                            CompetitorRegulationConstants::FIELD__TYPE_ID => 2,
                            CompetitorRegulationConstants::FIELD__HOST_ID => 2,
                        ],
                        [
                            CompetitorRegulationConstants::FIELD__EQUAL   => 2,
                            CompetitorRegulationConstants::FIELD__TYPE_ID => 3,
                            CompetitorRegulationConstants::FIELD__HOST_ID => 3,
                        ],
                        [
                            CompetitorRegulationConstants::FIELD__EQUAL   => 3,
                            CompetitorRegulationConstants::FIELD__TYPE_ID => 4,
                            CompetitorRegulationConstants::FIELD__HOST_ID => 4,
                        ],
                    ],
                ],
                [
                    [
                        CompetitorRegulationConstants::FIELD__EQUAL   => 0,
                        CompetitorRegulationConstants::FIELD__TYPE_ID => 1,
                        CompetitorRegulationConstants::FIELD__HOST_ID => 1,
                    ],
                ],
            ],
        ];
    }
    
    public function dataProviderFalse(): array
    {
        return [
            [
                [],
            ],
        ];
    }
}