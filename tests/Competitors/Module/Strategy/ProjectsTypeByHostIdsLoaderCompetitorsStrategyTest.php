<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Module\Strategy;

use Example\Competitors\Module\DataLoader\ProjectsTypeByHostIdsLoader;
use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Module\Strategy\ProjectsTypeByHostIdsLoaderStrategy;
use Example\Core\Components\Modular\AbstractStrategyPrototype;
use Example\Tests\Competitors\AbstractCompetitorsStrategyTest;

class ProjectsTypeByHostIdsLoaderCompetitorsStrategyTest extends AbstractCompetitorsStrategyTest
{
    /**
     * @var ProjectsTypeByHostIdsLoader|\PHPUnit_Framework_MockObject_MockObject
     */
    private $loader;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->loader = $this->getMockBuilder(ProjectsTypeByHostIdsLoader::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
    
    protected function createStrategy(): AbstractStrategyPrototype
    {
        return new ProjectsTypeByHostIdsLoaderStrategy();
    }
    
    protected function tearDown(): void
    {
        $this->loader = null;
        
        parent::tearDown();
    }
    
    public function testRun(): void
    {
        $this->container->data[HostTypeContainer::FIELD__HOST_IDS] = [1];
        
        static::assertTrue($this->strategy->shouldProcess());
        $this->strategy->prepare();
        
        $property = new \ReflectionProperty(ProjectsTypeByHostIdsLoaderStrategy::class, 'dataLoader');
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
                HostTypeContainer::FIELD__HOST_IDS                => [1],
                HostTypeContainer::FIELD__HOSTS_PROJECT_TYPE_LOAD => ['zzz'],
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