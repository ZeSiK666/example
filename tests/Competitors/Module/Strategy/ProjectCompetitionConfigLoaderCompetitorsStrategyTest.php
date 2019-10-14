<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Module\Strategy;

use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Module\HostTypeContext;
use Example\Competitors\Module\Strategy\ProjectCompetitionConfigLoaderStrategy;
use Example\Core\Components\Modular\AbstractStrategyPrototype;
use Example\Project\Module\DataLoader\ProjectCompetitionConfigByProjectIdLoader;
use Example\Tests\Competitors\AbstractCompetitorsStrategyTest;

class ProjectCompetitionConfigLoaderCompetitorsStrategyTest extends AbstractCompetitorsStrategyTest
{
    /**
     * @var ProjectCompetitionConfigByProjectIdLoader|\PHPUnit_Framework_MockObject_MockObject
     */
    private $loader;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->loader = $this->getMockBuilder(ProjectCompetitionConfigByProjectIdLoader::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
    
    protected function createStrategy(): AbstractStrategyPrototype
    {
        return new ProjectCompetitionConfigLoaderStrategy();
    }
    
    protected function tearDown(): void
    {
        $this->loader = null;
        
        parent::tearDown();
    }
    
    /**
     * @param array $params
     * @param array $expected
     *
     * @dataProvider dataProvider
     */
    public function testRun(array $params = [], array $expected = []): void
    {
        $this->context->set(HostTypeContext::FIELD__PROJECT_ID, 1);
        
        static::assertTrue($this->strategy->shouldProcess());
        $this->strategy->prepare();
        
        $property = new \ReflectionProperty(ProjectCompetitionConfigLoaderStrategy::class, 'dataLoader');
        $property->setAccessible(true);
        $property->setValue($this->strategy, $this->loader);
        
        $this->loader->expects(self::once())->method('getData')
            ->willReturn(
                $params
            );
        
        $this->strategy->run();
        
        static::assertSame(
            $expected,
            $this->strategy->getContainer()->data
        );
    }
    
    public function dataProvider(): array
    {
        return [
            [
                [
                    \ProjectCompetitionConfig::FIELD__COMPETITOR_TYPE_ID => 1,
                ],
                [
                    HostTypeContainer::FIELD__PROJECT_COMPETITION_TYPE_ID => 1,
                ],
            ],
            [
                [],
                [
                    HostTypeContainer::FIELD__PROJECT_COMPETITION_TYPE_ID => null,
                ],
            ],
        ];
    }
    
    public function dataProviderFalse(): array
    {
        return [
            [],
        ];
    }
}