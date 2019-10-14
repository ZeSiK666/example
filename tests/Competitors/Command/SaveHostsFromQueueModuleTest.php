<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Command;

use Example\AMQP\AMQPHelperInterface;
use Example\AnalyticsTop100\Module\DataLoader\BudgetHostsByNamesLoader;
use Example\Competitors\Command\SaveHostsFromQueueModule;
use Example\Competitors\Module\Saver\HostRatioDiffSaver;
use Example\Tests\Competitors\AbstractCompetitorsYmlTest;

class SaveHostsFromQueueModuleTest extends AbstractCompetitorsYmlTest
{
    /**
     * @var AMQPHelperInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $amqpHelper;
    /**
     * @var SaveHostsFromQueueModule
     */
    private $command;
    /**
     * @var BudgetHostsByNamesLoader|\PHPUnit_Framework_MockObject_MockObject
     */
    private $loader;
    /**
     * @var HostRatioDiffSaver|\PHPUnit_Framework_MockObject_MockObject
     */
    private $saver;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->amqpHelper = $this->getMockBuilder(AMQPHelperInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->loader = $this->getMockBuilder(BudgetHostsByNamesLoader::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->saver = $this->getMockBuilder(HostRatioDiffSaver::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->command = new SaveHostsFromQueueModule($this->amqpHelper, $this->loader, $this->saver);
    }
    
    protected function tearDown(): void
    {
        $this->amqpHelper = null;
        $this->loader     = null;
        $this->saver      = null;
        $this->command    = null;
        
        parent::tearDown();
    }
    
    public function testExcecute(): void
    {
        $this->amqpHelper->expects(self::once())->method('initiate');
        
        $this->loader->expects(self::once())
            ->method('getData')
            ->willReturn(
                [
                    [
                        \BudgetHost::FIELD__ID   => 1,
                        \BudgetHost::FIELD__HOST => 'qqq.ru',
                    ],
                    [
                        \BudgetHost::FIELD__ID   => 2,
                        \BudgetHost::FIELD__HOST => 'www.ru',
                    ],
                ]
            );
        
        $this->loader->expects(self::once())->method('flush');
        $this->loader->expects(self::once())
            ->method('configure')
            ->with(
                [
                    BudgetHostsByNamesLoader::CONF_HOST_NAMES => [
                        'qqqq.ru',
                        'www.ru',
                    ],
                ]
            );
        $this->loader->expects(self::once())->method('prepare');
        $this->loader->expects(self::once())
            ->method('getData')
            ->willReturn(
                [
                    [
                        \BudgetHost::FIELD__ID   => 10,
                        \BudgetHost::FIELD__HOST => 'www.ru',
                    ],
                ]
            );
        
        $this->saver->expects(self::once())
            ->method('execute')
            ->with(
                [
                    'hostFrom'   => [
                        \Host::FIELD__ID         => 2,
                        \Host::FIELD__PROJECT_ID => 2,
                    ],
                    'hostsToIds' => [
                        3,
                    ],
                ]
            );
        
        $this->amqpHelper->expects(self::once())
            ->method('publish')
            ->with(
                [
                    'stat_ready_id'   => 1,
                    'host_from_id'    => 2,
                    'from_name'       => 'qqqq.ru',
                    'from_project_id' => 2,
                    'host_to_id'      => '3',
                    'to_name'         => 'www.ru',
                    'to_project_id'   => '2',
                ]
            );
        
        $this->command->execute(
            [
                'stat_ready_id'   => 1,
                'host_from_id'    => 2,
                'from_name'       => 'qqqq.ru',
                'from_project_id' => 2,
                'host_to_id'      => '3',
                'to_name'         => 'www.ru',
                'to_project_id'   => '2',
            ]
        );
    }
}