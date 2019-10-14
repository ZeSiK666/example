<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Module;

use Example\AnalyticsTop100\Module\CompetitionContainer;
use Example\Competitors\Module\HostTypeContainer;
use Example\Competitors\Module\HostTypeContext;
use Example\Competitors\Module\HostTypePresenter;
use Example\Core\Components\Modular\AbstractContainerPrototype;
use Example\Core\Components\Modular\AbstractContextPrototype;
use Example\Tests\Competitors\AbstractCompetitorsYmlTest;

class HostTypePresenterTest extends AbstractCompetitorsYmlTest
{
    /**
     * @var AbstractContextPrototype
     */
    protected $context;
    /**
     * @var AbstractContainerPrototype
     */
    protected $container;
    /**
     * @var HostTypePresenter
     */
    private $presenter;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->context   = new HostTypeContext();
        $this->container = new HostTypeContainer();
        $this->presenter = new HostTypePresenter();
        
        $this->presenter->setContext($this->context);
        $this->presenter->setContainer($this->container);
    }
    
    protected function tearDown(): void
    {
        $this->context   = null;
        $this->container = null;
        $this->presenter = null;
        
        parent::tearDown();
    }
    
    /**
     * @param array $data
     * @param array $result
     * @param array $expected
     *
     * @dataProvider dataProvider
     */
    public function testFormatResult(array $data, array $result, array $expected): void
    {
        $this->container->data   = $data;
        $this->container->result = $result;
        
        self::assertSame($expected, $this->presenter->formatResult());
    }
    
    public function dataProvider(): array
    {
        return [
            [
                [
                    HostTypeContainer::FIELD__PROJECT_COMPETITION_TYPE_ID => 1,
                    HostTypeContainer::FIELD__COMPETITOR_TYPE_LOAD        => [
                        1 => 'www',
                        2 => 'eee',
                    ],
                    CompetitionContainer::FIELD__HOSTS                    => [
                        [
                            'qqqq',
                        ],
                    ],
                ],
                [
                    HostTypeContainer::FIELD__RESULT_HOSTS_TYPE_DATA => [
                        1 => [
                            \Host::FIELD__PROXY_HOST_ID => 3,
                        ],
                    ],
                    CompetitionContainer::RESULT__COMPETITOR_HOSTS   => [
                        1  => [
                            \Host::FIELD__ID            => 1,
                            \Host::FIELD__NAME          => 'qqqq',
                            \Host::FIELD__MAIN_HOST_ID  => 2,
                            \Host::FIELD__PROXY_HOST_ID => 3,
                        ],
                        15 => [
                            \Host::FIELD__ID            => 15,
                            \Host::FIELD__NAME          => 'www',
                            \Host::FIELD__MAIN_HOST_ID  => 25,
                            \Host::FIELD__PROXY_HOST_ID => 35,
                        ],
                    ],
                ],
                [
                    HostTypeContainer::PRESENTER__HOSTS                         => [
                        [
                            'qqqq',
                        ],
                    ],
                    HostTypeContainer::PRESENTER__PROJECT_COMPETITION_TYPE_ID   => 1,
                    HostTypeContainer::PRESENTER__PROJECT_COMPETITION_TYPE_NAME => 'www',
                    HostTypeContainer::PRESENTER__COMPETITOR_TYPES              => [
                        1 => 'www',
                        2 => 'eee',
                    ],
                    HostTypeContainer::PRESENTER__HOSTS_TYPE_DATA               => [
                        1 => [
                            \Host::FIELD__PROXY_HOST_ID => 3,
                        ],
                    ],
                    HostTypeContainer::PRESENTER__COMPETITOR_PROXY_HOST_IDS     => [
                        3  => 1,
                        35 => 15,
                    ],
                    HostTypeContainer::PRESENTER__FILTERED_COMPETITOR_HOSTS_IDS => [
                        3,
                    ],
                ],
            ],
            [
                [
                    HostTypeContainer::FIELD__PROJECT_COMPETITION_TYPE_ID => 0,
                    HostTypeContainer::FIELD__COMPETITOR_TYPE_LOAD        => [
                        1 => 'www',
                        2 => 'eee',
                    ],
                ],
                [
                    HostTypeContainer::FIELD__RESULT_HOSTS_TYPE_DATA => ['qqq'],
                    CompetitionContainer::RESULT__COMPETITOR_HOSTS   => [
                        1  => [
                            \Host::FIELD__ID            => 1,
                            \Host::FIELD__NAME          => 'qqqq',
                            \Host::FIELD__MAIN_HOST_ID  => 2,
                            \Host::FIELD__PROXY_HOST_ID => 3,
                        ],
                        15 => [
                            \Host::FIELD__ID            => 15,
                            \Host::FIELD__NAME          => 'www',
                            \Host::FIELD__MAIN_HOST_ID  => 25,
                            \Host::FIELD__PROXY_HOST_ID => 35,
                        ],
                    ],
                ],
                [
                    HostTypeContainer::PRESENTER__HOSTS                         => [],
                    HostTypeContainer::PRESENTER__PROJECT_COMPETITION_TYPE_ID   => 0,
                    HostTypeContainer::PRESENTER__PROJECT_COMPETITION_TYPE_NAME => '',
                    HostTypeContainer::PRESENTER__COMPETITOR_TYPES              => [
                        1 => 'www',
                        2 => 'eee',
                    ],
                    HostTypeContainer::PRESENTER__HOSTS_TYPE_DATA               => ['qqq'],
                    HostTypeContainer::PRESENTER__COMPETITOR_PROXY_HOST_IDS     => [
                        3  => 1,
                        35 => 15,
                    ],
                    HostTypeContainer::PRESENTER__FILTERED_COMPETITOR_HOSTS_IDS => [],
                ],
            ],
        ];
    }
}