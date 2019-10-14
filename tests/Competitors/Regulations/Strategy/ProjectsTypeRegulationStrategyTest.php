<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Regulations\Strategy;

use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Regulations\CompetitorRegulationConstants;
use Example\Competitors\Regulations\Strategy\ProjectsTypeRegulationStrategy;
use Example\Core\Components\Modular\AbstractStrategyPrototype;

class ProjectsTypeRegulationStrategyTest extends AbstractRegulationCompetitorsStrategyTest
{
    protected function createStrategy(): AbstractStrategyPrototype
    {
        return new ProjectsTypeRegulationStrategy();
    }
    
    public function dataProvider(): array
    {
        return [
            [
                [
                    self::FIELD__PROJECT_ID => 1,
                    self::FIELD__CLIENT_ID  => 0,
                    self::FIELD__DATA_KEY   => HostTypeContainer::FIELD__HOSTS_PROJECT_TYPE_LOAD,
                    self::FIELD__DATA       => [
                        [
                            \HostRatio::FIELD__HOST_TO_ID              => 2,
                            \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                            \HostRatio::FIELD__PROJECT_ID              => 1,
                        ],
                        [
                            \HostRatio::FIELD__HOST_TO_ID              => 2,
                            \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                            \HostRatio::FIELD__PROJECT_ID              => 2,
                        ],
                        [
                            \HostRatio::FIELD__HOST_TO_ID              => 2,
                            \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                            \HostRatio::FIELD__PROJECT_ID              => 3,
                        ],
                        [
                            \HostRatio::FIELD__HOST_TO_ID              => 3,
                            \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                            \HostRatio::FIELD__PROJECT_ID              => 1,
                        ],
                        [
                            \HostRatio::FIELD__HOST_TO_ID              => 3,
                            \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                            \HostRatio::FIELD__PROJECT_ID              => 2,
                        ],
                        [
                            \HostRatio::FIELD__HOST_TO_ID              => 3,
                            \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                            \HostRatio::FIELD__PROJECT_ID              => 4,
                        ],
                        [
                            \HostRatio::FIELD__HOST_TO_ID              => 3,
                            \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 2,
                            \HostRatio::FIELD__PROJECT_ID              => 4,
                        ],
                    ],
                ],
                'result' => [
                    '2;1;3' => [
                        CompetitorRegulationConstants::FIELD__HOST_ID  => 2,
                        CompetitorRegulationConstants::FIELD__TYPE_ID  => 1,
                        CompetitorRegulationConstants::FIELD__COUNT    => 3,
                        CompetitorRegulationConstants::FIELD__RATING   => $this->rating(),
                        CompetitorRegulationConstants::FIELD__FAVORITE => false,
                    ],
                    '3;1;3' => [
                        CompetitorRegulationConstants::FIELD__HOST_ID  => 3,
                        CompetitorRegulationConstants::FIELD__TYPE_ID  => 1,
                        CompetitorRegulationConstants::FIELD__COUNT    => 3,
                        CompetitorRegulationConstants::FIELD__RATING   => $this->rating(),
                        CompetitorRegulationConstants::FIELD__FAVORITE => false,
                    ],
                    '3;2;3' => [
                        CompetitorRegulationConstants::FIELD__HOST_ID  => 3,
                        CompetitorRegulationConstants::FIELD__TYPE_ID  => 2,
                        CompetitorRegulationConstants::FIELD__COUNT    => 1,
                        CompetitorRegulationConstants::FIELD__RATING   => $this->rating(),
                        CompetitorRegulationConstants::FIELD__FAVORITE => false,
                    ],
                ],
            ],
        ];
    }
}