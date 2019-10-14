<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Module\Saver;

use Example\AnalyticsTop100\Module\DataLoader\HostRatioLoader;
use Example\AnalyticsTop100\Module\Command\InsertDataCommand;
use Example\Competitors\Module\Saver\HostRatioDiffSaver;
use Example\Tests\Competitors\Command\AbstractCommandYmlTest;

class HostRatioDiffSaverTest extends AbstractCommandYmlTest
{
    /**
     * @var InsertDataCommand|\PHPUnit_Framework_MockObject_MockObject
     */
    private $insertCommand;
    /**
     * @var HostRatioDiffSaver
     */
    private $saver;
    /**
     * @var HostRatioLoader|\PHPUnit_Framework_MockObject_MockObject
     */
    private $loader;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->loader = $this->getMockBuilder(HostRatioLoader::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->insertCommand = $this->getMockBuilder(InsertDataCommand::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->saver = new HostRatioDiffSaver($this->insertCommand, $this->loader);
    }
    
    protected function tearDown(): void
    {
        $this->insertCommand = null;
        $this->loader        = null;
        $this->saver         = null;
        
        parent::tearDown();
    }
    
    /**
     * @param array $params
     *
     * @dataProvider dataProvider
     */
    public function testExcecute(array $params): void
    {
        $this->loader->expects(self::once())
            ->method('configure')
            ->with(
                [
                    HostRatioLoader::CONF_HOST_FROM_ID => $params['hostFrom'][\Host::FIELD__ID],
                    HostRatioLoader::CONF_HOSTS_TO_ID  => $params['hostsToIds'],
                ]
            );
        $this->loader->expects(self::once())->method('prepare');
        
        $this->loader->expects(self::once())->method('getData')->willReturn(
            [
                [
                    \HostRatio::FIELD__ID           => 1,
                    \HostRatio::FIELD__HOST_FROM_ID => 4,
                    \HostRatio::FIELD__HOST_FROM_ID => 1,
                ],
                [
                    \HostRatio::FIELD__ID           => 1,
                    \HostRatio::FIELD__HOST_FROM_ID => 4,
                    \HostRatio::FIELD__HOST_TO_ID   => 2,
                ],
            ]
        );
        
        $this->insertCommand->expects(self::once())
            ->method('execute')
            ->with(
                [
                    InsertDataCommand::PARAM_TABLE => \HostRatio::TABLE_NAME,
                    InsertDataCommand::PARAM_DATA  => [
                        [
                            \HostRatio::FIELD__HOST_FROM_ID => 4,
                            \HostRatio::FIELD__HOST_TO_ID   => 3,
                            \HostRatio::FIELD__PROJECT_ID   => 1,
                        ],
                    ],
                ]
            );
        
        $this->saver->execute($params);
    }
    
    public function dataProvider(): array
    {
        return [
            [
                [
                    'hostFrom'   => [
                        \Host::FIELD__ID         => 4,
                        \Host::FIELD__PROJECT_ID => 1,
                    ],
                    'hostsToIds' => [
                        2,
                        3,
                    ],
                ],
            ],
        ];
    }
}