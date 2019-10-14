<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Module\Strategy;

use Example\Competitors\Module\DataLoader\HostsRatingTypeByHostIdsNotClientIdLoader;
use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Module\HostTypeContext;
use Example\Competitors\Module\Strategy\HostsRatingTypeByHostIdsNotClientIdLoaderStrategy;
use Example\Core\Components\Modular\AbstractStrategyPrototype;
use Example\Tests\Competitors\AbstractCompetitorsStrategyTest;

class HostsRatingTypeByHostIdsNotClientIdLoaderCompetitorsStrategyTest extends AbstractCompetitorsStrategyTest
{
    /**
     * @var HostsRatingTypeByHostIdsNotClientIdLoader|\PHPUnit_Framework_MockObject_MockObject
     */
    private $loader;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->loader = $this->getMockBuilder(HostsRatingTypeByHostIdsNotClientIdLoader::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
    
    protected function createStrategy(): AbstractStrategyPrototype
    {
        return new HostsRatingTypeByHostIdsNotClientIdLoaderStrategy();
    }
    
    protected function tearDown(): void
    {
        $this->loader = null;
        
        parent::tearDown();
    }
    
    public function testRun(): void
    {
        $this->context->set(HostTypeContext::FIELD__PROJECT_ID, 1);
        $this->container->data[HostTypeContainer::FIELD__HOST_IDS] = [1];
        
        static::assertTrue($this->strategy->shouldProcess());
        $this->strategy->prepare();
        
        $property = new \ReflectionProperty(HostsRatingTypeByHostIdsNotClientIdLoaderStrategy::class, 'dataLoader');
        $property->setAccessible(true);
        $property->setValue($this->strategy, $this->loader);
        
        $this->loader->expects(self::once())->method('getData')
            ->willReturn(
                [
                    'zzz',
                ]
            );
        
        $this->strategy->run();
        
        static::assertSame(
            [
                HostTypeContainer::FIELD__HOST_IDS                          => [1],
                HostTypeContainer::FIELD__HOSTS_RATING_TYPE_NOT_CLIENT_LOAD => ['zzz'],
            ],
            $this->strategy->getContainer()->data
        );
    }
    
    public function dataProviderFalse(): array
    {
        return [
            [],
        ];
    }
}