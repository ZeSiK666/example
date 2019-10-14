<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Module\DataLoader;

use Example\Competitors\Module\DataLoader\HostsRatingTypeByHostIdsClientIdLoader;
use Example\Core\Components\Modular\AbstractDataLoaderPrototype;

class HostsRatingTypeByProjectIdLoaderTest extends AbstractHostsRatingLoaderTest
{
    protected function createLoader(): AbstractDataLoaderPrototype
    {
        return new HostsRatingTypeByHostIdsClientIdLoader();
    }
    
    public function dataProvider(): array
    {
        return [
            [
                'client_id' => 1,
                [
                    2,
                    3,
                ],
                [
                    [
                        \HostRatio::FIELD__HOST_FROM_ID            => 1,
                        \HostRatio::FIELD__HOST_TO_ID              => 2,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                        \HostRatio::FIELD__PROJECT_ID              => 1,
                        \HostRatingType::FIELD__CLIENT_ID          => 1,
                        \HostRatingType::FIELD__FAVORITE           => false,
                    ],
                    [
                        \HostRatio::FIELD__HOST_FROM_ID            => 1,
                        \HostRatio::FIELD__HOST_TO_ID              => 2,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                        \HostRatio::FIELD__PROJECT_ID              => 2,
                        \HostRatingType::FIELD__CLIENT_ID          => 1,
                        \HostRatingType::FIELD__FAVORITE           => true,
                    ],
                    [
                        \HostRatio::FIELD__HOST_FROM_ID            => 1,
                        \HostRatio::FIELD__HOST_TO_ID              => 2,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                        \HostRatio::FIELD__PROJECT_ID              => 10,
                        \HostRatingType::FIELD__CLIENT_ID          => 1,
                        \HostRatingType::FIELD__FAVORITE           => false,
                    ],
                    [
                        \HostRatio::FIELD__HOST_FROM_ID            => 1,
                        \HostRatio::FIELD__HOST_TO_ID              => 3,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                        \HostRatio::FIELD__PROJECT_ID              => 1,
                        \HostRatingType::FIELD__CLIENT_ID          => 1,
                        \HostRatingType::FIELD__FAVORITE           => false,
                    ],
                    [
                        \HostRatio::FIELD__HOST_FROM_ID            => 2,
                        \HostRatio::FIELD__HOST_TO_ID              => 3,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                        \HostRatingType::FIELD__PROJECT_ID         => 2,
                        \HostRatingType::FIELD__CLIENT_ID          => 1,
                        \HostRatingType::FIELD__FAVORITE           => false,
                    ],
                    [
                        \HostRatio::FIELD__HOST_FROM_ID            => 1,
                        \HostRatio::FIELD__HOST_TO_ID              => 3,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                        \HostRatio::FIELD__PROJECT_ID              => 2,
                        \HostRatingType::FIELD__CLIENT_ID          => 1,
                        \HostRatingType::FIELD__FAVORITE           => true,
                    ],
                    [
                        \HostRatio::FIELD__HOST_FROM_ID            => 2,
                        \HostRatio::FIELD__HOST_TO_ID              => 3,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 2,
                        \HostRatio::FIELD__PROJECT_ID              => 2,
                        \HostRatingType::FIELD__CLIENT_ID          => 1,
                        \HostRatingType::FIELD__FAVORITE           => false,
                    ],
                ],
            ],
        ];
    }
    
    /**
     * @param int   $clientId
     * @param array $hostIds
     * @param array $expected
     *
     * @dataProvider dataProviderFavorite
     */
    public function testPrepareFavorite(int $clientId, array $hostIds, array $expected): void
    {
        $this->loader->configure(
            [
                HostsRatingTypeByHostIdsClientIdLoader::CONF_CLIENT_ID => $clientId,
                HostsRatingTypeByHostIdsClientIdLoader::CONF_HOST_IDS  => $hostIds,
                HostsRatingTypeByHostIdsClientIdLoader::CONF_FAVORITE  => true,
            ]
        );
        
        $this->loader->prepare();
        
        static::assertSame($expected, $this->loader->getData());
    }
    
    public function dataProviderFavorite(): array
    {
        return [
            [
                'client_id' => 1,
                [
                    2,
                    3,
                ],
                [
                    [
                        \HostRatio::FIELD__HOST_FROM_ID            => 1,
                        \HostRatio::FIELD__HOST_TO_ID              => 2,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                        \HostRatio::FIELD__PROJECT_ID              => 2,
                        \HostRatingType::FIELD__CLIENT_ID          => 1,
                        \HostRatingType::FIELD__FAVORITE           => true,
                    ],
                    [
                        \HostRatio::FIELD__HOST_FROM_ID            => 1,
                        \HostRatio::FIELD__HOST_TO_ID              => 3,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                        \HostRatio::FIELD__PROJECT_ID              => 2,
                        \HostRatingType::FIELD__CLIENT_ID          => 1,
                        \HostRatingType::FIELD__FAVORITE           => true,
                    ],
                ],
            ],
        ];
    }
}