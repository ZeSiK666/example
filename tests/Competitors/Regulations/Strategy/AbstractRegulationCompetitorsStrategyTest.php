<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Regulations\Strategy;

use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Module\HostTypeContext;
use Example\Competitors\Regulations\CompetitorRegulationConstants;
use Example\Tests\Competitors\AbstractCompetitorsStrategyTest;

abstract class AbstractRegulationCompetitorsStrategyTest extends AbstractCompetitorsStrategyTest
{
    protected const FIELD__PROJECT_ID = 'project_id';
    protected const FIELD__CLIENT_ID  = 'client_id';
    protected const FIELD__DATA_KEY   = 'data_key';
    protected const FIELD__DATA       = 'data';
    
    /**
     * @param array $params
     * @param array $expected
     *
     * @dataProvider dataProvider
     */
    public function testRun(array $params = [], array $expected = []): void
    {
        $this->context->set(HostTypeContext::FIELD__PROJECT_ID, $params[self::FIELD__PROJECT_ID]);
        $this->context->set(HostTypeContext::FIELD__CLIENT_ID, $params[self::FIELD__CLIENT_ID]);
        $this->container->data[$params[self::FIELD__DATA_KEY]] = $params[self::FIELD__DATA];
        
        self::assertTrue($this->strategy->shouldProcess());
        $this->strategy->prepare();
        $this->strategy->run();
        
        self::assertSame($expected, $this->dataResult());
    }
    
    protected function dataResult(): array
    {
        return $this->container->data[HostTypeContainer::FIELD__HOSTS_TYPE_DATA] ?? [];
    }
    
    protected function rating(): int
    {
        return CompetitorRegulationConstants::RATINGS_TYPE_HOST[$this->createStrategy()->getId(
        )][CompetitorRegulationConstants::FIELD__RATING];
    }
    
    public function dataProviderFalse(): array
    {
        return [
            [
                [],
            ],
            [
                [
                    HostTypeContext::FIELD__PROJECT_ID => 1,
                ],
            ],
            [
                [HostTypeContext::FIELD__CLIENT_ID => 1,],
            ],
            [
                [HostTypeContainer::FIELD__HOSTS_RATING_TYPE_LOAD => ['qqq'],],
            ],
            [
                [
                    HostTypeContext::FIELD__PROJECT_ID => 1,
                    HostTypeContext::FIELD__CLIENT_ID  => 1,
                ],
            ],
            [
                [
                    HostTypeContext::FIELD__PROJECT_ID               => 1,
                    HostTypeContainer::FIELD__HOSTS_RATING_TYPE_LOAD => ['qqq'],
                ],
            ],
            [
                [
                    HostTypeContext::FIELD__PROJECT_ID               => 1,
                    HostTypeContext::FIELD__CLIENT_ID                => 1,
                    HostTypeContainer::FIELD__HOSTS_RATING_TYPE_LOAD => [],
                ],
            ],
        ];
    }
    
    abstract public function dataProvider(): array;
}