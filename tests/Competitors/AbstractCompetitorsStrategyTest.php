<?php

declare(strict_types=1);

namespace Example\Tests\Competitors;

use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Module\HostTypeContext;
use PHPUnit\Framework\TestCase;
use Example\Core\Components\Modular\AbstractContainerPrototype;
use Example\Core\Components\Modular\AbstractContextPrototype;
use Example\Core\Components\Modular\AbstractStrategyPrototype;

abstract class AbstractCompetitorsStrategyTest extends TestCase
{
    /**
     * @var AbstractStrategyPrototype
     */
    protected $strategy;
    /**
     * @var AbstractContextPrototype
     */
    protected $context;
    /**
     * @var AbstractContainerPrototype
     */
    protected $container;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->context   = new HostTypeContext();
        $this->container = new HostTypeContainer();
        $this->strategy  = $this->createStrategy();
        
        $this->strategy->setContext($this->context);
        $this->strategy->setContainer($this->container);
    }
    
    protected function tearDown(): void
    {
        $this->strategy  = null;
        $this->context   = null;
        $this->container = null;
        
        parent::tearDown();
    }
    
    /**
     * @param array $keys
     *
     * @dataProvider dataProviderFalse
     */
    public function testShouldProcessFalse(array $keys = []): void
    {
        $this->context = new HostTypeContext();
        $this->context->fill($keys);
        
        static::assertFalse(
            $this->strategy->shouldProcess()
        );
    }
    
    abstract protected function createStrategy(): AbstractStrategyPrototype;
    
    abstract public function dataProviderFalse(): array;
    
    abstract public function testRun(): void;
}