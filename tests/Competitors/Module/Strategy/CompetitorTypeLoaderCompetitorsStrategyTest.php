<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Module\Strategy;

use Example\Competitors\Module\DataLoader\CompetitiorTypeLoader;
use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Module\Strategy\CompetitorTypeLoaderStrategy;
use Example\Core\Components\Modular\AbstractStrategyPrototype;
use Example\Tests\Competitors\AbstractCompetitorsStrategyTest;

class CompetitorTypeLoaderCompetitorsStrategyTest extends AbstractCompetitorsStrategyTest
{
    /**
     * @var CompetitiorTypeLoader|\PHPUnit_Framework_MockObject_MockObject
     */
    private $loader;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->loader = $this->getMockBuilder(CompetitiorTypeLoader::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $property = new \ReflectionProperty(CompetitorTypeLoaderStrategy::class, 'dataLoader');
        $property->setAccessible(true);
        $property->setValue($this->strategy, $this->loader);
    }
    
    protected function createStrategy(): AbstractStrategyPrototype
    {
        return new CompetitorTypeLoaderStrategy();
    }
    
    protected function tearDown(): void
    {
        $this->loader = null;
        
        parent::tearDown();
    }
    
    public function testRun(): void
    {
        $this->loader->expects(self::once())->method('getData')
            ->willReturn(
                [
                    [
                        \CompetitorType::FIELD__ID   => 1,
                        \CompetitorType::FIELD__NAME => 'qqqq',
                    ],
                    [
                        \CompetitorType::FIELD__ID   => 2,
                        \CompetitorType::FIELD__NAME => 'www',
                    ],
                ]
            );
        
        static::assertTrue($this->strategy->shouldProcess());
        $this->strategy->run();
        
        static::assertSame(
            [
                HostTypeContainer::FIELD__COMPETITOR_TYPE_LOAD => [
                    1 => 'qqqq',
                    2 => 'www',
                ],
            ],
            $this->strategy->getContainer()->data
        );
    }
    
    /**
     * @param array $keys
     *
     * @dataProvider dataProviderFalse
     */
    public function testShouldProcessFalse(array $keys = []): void
    {
        static::assertTrue(
            $this->strategy->shouldProcess()
        );
    }
    
    public function dataProviderFalse(): array
    {
        return [
            [],
        ];
    }
}