<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Module\Strategy;

use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Module\HostTypeContext;
use Example\Competitors\Module\Strategy\HostTypeRegulationStrategy;
use Example\Competitors\Regulations\HostTypeRegulationProcessor;
use Example\Core\Components\Modular\AbstractStrategyPrototype;
use Example\Tests\Competitors\AbstractCompetitorsStrategyTest;

class HostTypeRegulationCompetitorsStrategyTest extends AbstractCompetitorsStrategyTest
{
    /**
     * @var HostTypeRegulationProcessor|\PHPUnit_Framework_MockObject_MockObject
     */
    private $regulationProcessor;
    
    protected function createStrategy(): AbstractStrategyPrototype
    {
        return new HostTypeRegulationStrategy();
    }
    
    protected function tearDown(): void
    {
        parent::tearDown();
        
        $this->regulationProcessor = null;
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
                    HostTypeContext::FIELD__CLIENT_ID                => 1,
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
    
    public function testRun(): void
    {
        $this->context->set(HostTypeContext::FIELD__PROJECT_ID, 1);
        $this->context->set(HostTypeContext::FIELD__CLIENT_ID, 2);
        $this->container->data[HostTypeContainer::FIELD__HOSTS_RATING_TYPE_LOAD] = ['qqqq'];
        
        static::assertTrue(
            $this->strategy->shouldProcess()
        );
        
        $this->strategy->prepare();
        
        $this->regulationProcessor = $this->getMockBuilder(HostTypeRegulationProcessor::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $property = new \ReflectionProperty(HostTypeRegulationStrategy::class, 'regulationProcessor');
        $property->setAccessible(true);
        $property->setValue($this->strategy, $this->regulationProcessor);
        
        $this->regulationProcessor->expects(self::once())->method('getResult')->willReturn(['www']);
        
        $this->strategy->run();
        
        static::assertSame(
            [
                HostTypeContainer::FIELD__RESULT_HOSTS_TYPE_DATA => ['www'],
            ],
            $this->strategy->getContainer()->result
        );
    }
}