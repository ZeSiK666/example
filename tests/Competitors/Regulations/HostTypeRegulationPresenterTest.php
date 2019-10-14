<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Regulations;

use PHPUnit\Framework\TestCase;
use Example\Competitors\CompetitorsDirectionConstants;
use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Module\HostTypeContext;
use Example\Competitors\Regulations\CompetitorRegulationConstants;
use Example\Competitors\Regulations\HostTypeRegulationPresenter;

class HostTypeRegulationPresenterTest extends TestCase
{
    /**
     * @var HostTypeRegulationPresenter
     */
    private $presenter;
    /**
     * @var HostTypeContainer
     */
    private $container;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->container = new HostTypeContainer();
        $this->presenter = new HostTypeRegulationPresenter();
        
        $this->presenter->setContainer($this->container);
    }
    
    protected function tearDown(): void
    {
        $this->presenter = null;
        $this->container = null;
        
        parent::tearDown();
    }
    
    /**
     * @param int   $projectCompetitorTypeId
     * @param array $competitorType
     * @param array $data
     * @param array $expected
     *
     * @dataProvider dataProvider
     */
    public function testFormatResult(
        int $projectCompetitorTypeId,
        array $competitorType,
        array $data,
        array $expected): void
    {
        $this->container->data[HostTypeContainer::FIELD__PROJECT_COMPETITION_TYPE_ID] = $projectCompetitorTypeId;
        $this->container->data[HostTypeContainer::FIELD__COMPETITOR_TYPE_LOAD]        = $competitorType;
        $this->container->data[HostTypeContainer::FIELD__HOSTS_TYPE_DATA]             = $data;
        
        self::assertSame($expected, $this->presenter->formatResult());
    }
    
    public function dataProvider(): array
    {
        return [
            [
                'project_competitior_type_id' => 1,
                'competitor_type'             => [
                    1 => 'type_1',
                    2 => 'type_2',
                    3 => 'type_n',
                ],
                'data'                        => [
                    [
                        CompetitorRegulationConstants::FIELD__HOST_ID  => 1,
                        CompetitorRegulationConstants::FIELD__TYPE_ID  => 1,
                        CompetitorRegulationConstants::FIELD__COUNT    => 1,
                        CompetitorRegulationConstants::FIELD__RATING   => 1,
                        CompetitorRegulationConstants::FIELD__FAVORITE => true,
                    ],
                    [
                        CompetitorRegulationConstants::FIELD__HOST_ID => 1,
                        CompetitorRegulationConstants::FIELD__TYPE_ID => 2,
                        CompetitorRegulationConstants::FIELD__COUNT   => 3,
                        CompetitorRegulationConstants::FIELD__RATING  => 3,
                    ],
                    [
                        CompetitorRegulationConstants::FIELD__HOST_ID => 1,
                        CompetitorRegulationConstants::FIELD__TYPE_ID => 3,
                        CompetitorRegulationConstants::FIELD__COUNT   => 1,
                        CompetitorRegulationConstants::FIELD__RATING  => 2,
                    ],
                    [
                        CompetitorRegulationConstants::FIELD__HOST_ID => 1,
                        CompetitorRegulationConstants::FIELD__TYPE_ID => 5,
                        CompetitorRegulationConstants::FIELD__COUNT   => 5,
                        CompetitorRegulationConstants::FIELD__RATING  => 3,
                    ],
                    [
                        CompetitorRegulationConstants::FIELD__HOST_ID => 1,
                        CompetitorRegulationConstants::FIELD__TYPE_ID => 4,
                        CompetitorRegulationConstants::FIELD__COUNT   => 2,
                        CompetitorRegulationConstants::FIELD__RATING  => 2,
                    ],
                    //
                    [
                        CompetitorRegulationConstants::FIELD__HOST_ID => 2,
                        CompetitorRegulationConstants::FIELD__TYPE_ID => 2,
                        CompetitorRegulationConstants::FIELD__COUNT   => 3,
                        CompetitorRegulationConstants::FIELD__RATING  => 3,
                    ],
                    [
                        CompetitorRegulationConstants::FIELD__HOST_ID => 2,
                        CompetitorRegulationConstants::FIELD__TYPE_ID => 3,
                        CompetitorRegulationConstants::FIELD__COUNT   => 1,
                        CompetitorRegulationConstants::FIELD__RATING  => 2,
                    ],
                    [
                        CompetitorRegulationConstants::FIELD__HOST_ID => 2,
                        CompetitorRegulationConstants::FIELD__TYPE_ID => 5,
                        CompetitorRegulationConstants::FIELD__COUNT   => 5,
                        CompetitorRegulationConstants::FIELD__RATING  => 3,
                    ],
                    [
                        CompetitorRegulationConstants::FIELD__HOST_ID => 2,
                        CompetitorRegulationConstants::FIELD__TYPE_ID => 4,
                        CompetitorRegulationConstants::FIELD__COUNT   => 2,
                        CompetitorRegulationConstants::FIELD__RATING  => 2,
                    ],
                    //
                    [
                        CompetitorRegulationConstants::FIELD__HOST_ID => 5,
                        CompetitorRegulationConstants::FIELD__TYPE_ID => 2,
                        CompetitorRegulationConstants::FIELD__COUNT   => 3,
                        CompetitorRegulationConstants::FIELD__RATING  => 3,
                    ],
                    [
                        CompetitorRegulationConstants::FIELD__HOST_ID => 5,
                        CompetitorRegulationConstants::FIELD__TYPE_ID => 1,
                        CompetitorRegulationConstants::FIELD__COUNT   => 1,
                        CompetitorRegulationConstants::FIELD__RATING  => 3,
                    ],
                    [
                        CompetitorRegulationConstants::FIELD__HOST_ID => 5,
                        CompetitorRegulationConstants::FIELD__TYPE_ID => 3,
                        CompetitorRegulationConstants::FIELD__COUNT   => 3,
                        CompetitorRegulationConstants::FIELD__RATING  => 3,
                    ],
                ],
                'expected'                    => [
                    1 => [
                        CompetitorRegulationConstants::FIELD__HOST_ID     => 1,
                        CompetitorRegulationConstants::FIELD__TYPE_ID     => 1,
                        CompetitorRegulationConstants::FIELD__COUNT       => 1,
                        CompetitorRegulationConstants::FIELD__RATING      => 1,
                        CompetitorRegulationConstants::FIELD__FAVORITE    => true,
                        CompetitorRegulationConstants::FIELD__NAME        => 'type_1',
                        CompetitorRegulationConstants::FIELD__EQUAL_TITLE => CompetitorsDirectionConstants::COMPETITOR_TYPE_STRAIGHT,
                        CompetitorRegulationConstants::FIELD__EQUAL       => true,
                    ],
                    2 => [
                        CompetitorRegulationConstants::FIELD__HOST_ID     => 2,
                        CompetitorRegulationConstants::FIELD__TYPE_ID     => 4,
                        CompetitorRegulationConstants::FIELD__COUNT       => 2,
                        CompetitorRegulationConstants::FIELD__RATING      => 2,
                        CompetitorRegulationConstants::FIELD__NAME        => '',
                        CompetitorRegulationConstants::FIELD__EQUAL_TITLE => CompetitorsDirectionConstants::COMPETITOR_TYPE_COMMON,
                    ],
                    5 => [
                        CompetitorRegulationConstants::FIELD__HOST_ID     => 5,
                        CompetitorRegulationConstants::FIELD__TYPE_ID     => 2,
                        CompetitorRegulationConstants::FIELD__COUNT       => 3,
                        CompetitorRegulationConstants::FIELD__RATING      => 3,
                        CompetitorRegulationConstants::FIELD__NAME        => 'type_2',
                        CompetitorRegulationConstants::FIELD__EQUAL_TITLE => CompetitorsDirectionConstants::COMPETITOR_TYPE_COMMON,
                    ],
                ],
            ],
        ];
    }
}