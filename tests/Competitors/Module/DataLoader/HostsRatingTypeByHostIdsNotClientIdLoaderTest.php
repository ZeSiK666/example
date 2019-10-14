<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Module\DataLoader;

use Example\Competitors\Module\DataLoader\HostsRatingTypeByHostIdsNotClientIdLoader;
use Example\Core\Components\Modular\AbstractDataLoaderPrototype;

class HostsRatingTypeByHostIdsNotClientIdLoaderTest extends AbstractHostsRatingLoaderTest
{
    protected function createLoader(): AbstractDataLoaderPrototype
    {
        return new HostsRatingTypeByHostIdsNotClientIdLoader();
    }
    
    public function dataProvider(): array
    {
        return [
            [
                'client_id' => 2,
                [
                    1,
                    2,
                    3,
                ],
                [
                    [
                        \HostRatio::FIELD__HOST_FROM_ID            => 1,
                        \HostRatio::FIELD__HOST_TO_ID              => 2,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                        \HostRatingType::FIELD__CLIENT_ID          => 1,
                    ],
                    [
                        \HostRatio::FIELD__HOST_FROM_ID            => 1,
                        \HostRatio::FIELD__HOST_TO_ID              => 2,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                        \HostRatingType::FIELD__CLIENT_ID          => 1,
                    ],
                    [
                        \HostRatio::FIELD__HOST_FROM_ID            => 1,
                        \HostRatio::FIELD__HOST_TO_ID              => 2,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                        \HostRatingType::FIELD__CLIENT_ID          => 1,
                    ],
                    [
                        \HostRatio::FIELD__HOST_FROM_ID            => 1,
                        \HostRatio::FIELD__HOST_TO_ID              => 3,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                        \HostRatingType::FIELD__CLIENT_ID          => 1,
                    ],
                    [
                        \HostRatio::FIELD__HOST_FROM_ID            => 2,
                        \HostRatio::FIELD__HOST_TO_ID              => 3,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                        \HostRatingType::FIELD__CLIENT_ID          => 1,
                    ],
                    [
                        \HostRatio::FIELD__HOST_FROM_ID            => 1,
                        \HostRatio::FIELD__HOST_TO_ID              => 3,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                        \HostRatingType::FIELD__CLIENT_ID          => 1,
                    ],
                    [
                        \HostRatio::FIELD__HOST_FROM_ID            => 2,
                        \HostRatio::FIELD__HOST_TO_ID              => 3,
                        \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 2,
                        \HostRatingType::FIELD__CLIENT_ID          => 1,
                    ],
                ],
            ],
        ];
    }
}