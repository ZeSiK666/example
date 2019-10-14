<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Module\DataLoader;

use Example\Competitors\Module\DataLoader\HostsRatingTypeByHostIdsClientIdLoader;
use Example\Core\Components\Modular\AbstractDataLoaderPrototype;
use Example\Database\DatabaseConstants;
use Example\Tests\Competitors\AbstractCompetitorsYmlTest;

abstract class AbstractHostsRatingLoaderTest extends AbstractCompetitorsYmlTest
{
    /**
     * @var AbstractDataLoaderPrototype
     */
    protected $loader;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        \Y::dbCmd(
            sprintf('DELETE FROM %s; ALTER SEQUENCE dictionary.host_id_seq RESTART WITH 1;', \Host::TABLE_NAME),
            \Host::DATABASE_CONNECTOR_NAME
        )->execute();
        
        \Y::dbCmd(
            sprintf(
                'DELETE FROM %s; ALTER SEQUENCE competition.host_ratio_id_seq RESTART WITH 1;',
                \HostRatio::TABLE_NAME
            ),
            \HostRatingType::DATABASE_CONNECTOR_NAME
        )->execute();
        
        \Y::dbCmd(
            sprintf(
                'DELETE FROM %1s; ALTER SEQUENCE competition.host_rating_type_id_seq RESTART WITH 1;',
                \HostRatingType::TABLE_NAME
            ),
            \HostRatingType::DATABASE_CONNECTOR_NAME
        )->execute();
        
        $this->loader = $this->createLoader();
        
        $this->loadData();
    }
    
    protected function tearDown(): void
    {
        parent::tearDown();
        
        $this->loader = null;
    }
    
    private function loadData(): void
    {
        foreach ($this->data() as $table => $data) {
            \Y::massDataInsert(
                \Y::dbOriginal(DatabaseConstants::DATABASE_CONNECTION_NAME_PG_FAST),
                $table,
                $data
            );
        }
    }
    
    /**
     * @param int   $clientId
     * @param array $hostIds
     * @param array $expected
     *
     * @dataProvider dataProvider
     */
    public function testPrepare(int $clientId, array $hostIds, array $expected): void
    {
        $this->loader->flush();
        
        $this->loader->configure(
            [
                HostsRatingTypeByHostIdsClientIdLoader::CONF_CLIENT_ID => $clientId,
                HostsRatingTypeByHostIdsClientIdLoader::CONF_HOST_IDS  => $hostIds,
            ]
        );
        
        $this->loader->prepare();
        
        static::assertSame($expected, $this->loader->getData());
    }
    
    private function data(): array
    {
        return [
            \Host::TABLE_NAME           => [
                [
                    \Host::FIELD__MAIN_HOST_ID  => 1,
                    \Host::FIELD__PROXY_HOST_ID => 1,
                    \Host::FIELD__PROJECT_ID    => 1,
                    \Host::FIELD__NAME          => 'test.ru',
                ],
                [
                    \Host::FIELD__MAIN_HOST_ID  => 2,
                    \Host::FIELD__PROXY_HOST_ID => 2,
                    \Host::FIELD__PROJECT_ID    => 2,
                    \Host::FIELD__NAME          => 'qqq.ru',
                ],
                [
                    \Host::FIELD__MAIN_HOST_ID  => 0,
                    \Host::FIELD__PROXY_HOST_ID => 3,
                    \Host::FIELD__PROJECT_ID    => 3,
                    \Host::FIELD__NAME          => 'eee.ru',
                ],
                [
                    \Host::FIELD__MAIN_HOST_ID  => 0,
                    \Host::FIELD__PROXY_HOST_ID => 4,
                    \Host::FIELD__PROJECT_ID    => 0,
                    \Host::FIELD__NAME          => 'rrr.ru',
                ],
                [
                    \Host::FIELD__MAIN_HOST_ID  => 0,
                    \Host::FIELD__PROXY_HOST_ID => 5,
                    \Host::FIELD__PROJECT_ID    => 4,
                    \Host::FIELD__NAME          => 'ttt.ru',
                ],
            ],
            \HostRatio::TABLE_NAME      => [
                [
                    \HostRatio::FIELD__HOST_FROM_ID => 1,
                    \HostRatio::FIELD__HOST_TO_ID   => 2,
                    \HostRatio::FIELD__PROJECT_ID   => 1,
                ],
                [
                    \HostRatio::FIELD__HOST_FROM_ID => 1,
                    \HostRatio::FIELD__HOST_TO_ID   => 3,
                    \HostRatio::FIELD__PROJECT_ID   => 1,
                ],
                [
                    \HostRatio::FIELD__HOST_FROM_ID => 2,
                    \HostRatio::FIELD__HOST_TO_ID   => 3,
                    \HostRatio::FIELD__PROJECT_ID   => 2,
                ],
                [
                    \HostRatio::FIELD__HOST_FROM_ID => 4,
                    \HostRatio::FIELD__HOST_TO_ID   => 1,
                    \HostRatio::FIELD__PROJECT_ID   => 4,
                ],
                [
                    \HostRatio::FIELD__HOST_FROM_ID => 3,
                    \HostRatio::FIELD__HOST_TO_ID   => 1,
                    \HostRatio::FIELD__PROJECT_ID   => 3,
                ],
                [
                    \HostRatio::FIELD__HOST_FROM_ID => 3,
                    \HostRatio::FIELD__HOST_TO_ID   => 2,
                    \HostRatio::FIELD__PROJECT_ID   => 3,
                ],
                [
                    \HostRatio::FIELD__HOST_FROM_ID => 3,
                    \HostRatio::FIELD__HOST_TO_ID   => 4,
                    \HostRatio::FIELD__PROJECT_ID   => 3,
                ],
            ],
            \HostRatingType::TABLE_NAME => [
                [
                    \HostRatingType::FIELD__HOST_RATIO_ID      => 1,
                    \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                    \HostRatingType::FIELD__PROJECT_ID         => 1,
                    \HostRatingType::FIELD__CLIENT_ID          => 1,
                    \HostRatingType::FIELD__FAVORITE           => 0,
                    \HostRatingType::FIELD__CREATED_BY         => 1,
                    \HostRatingType::FIELD__UPDATED_BY         => 1,
                ],
                [
                    \HostRatingType::FIELD__HOST_RATIO_ID      => 1,
                    \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                    \HostRatingType::FIELD__PROJECT_ID         => 2,
                    \HostRatingType::FIELD__CLIENT_ID          => 1,
                    \HostRatingType::FIELD__FAVORITE           => 1,
                    \HostRatingType::FIELD__CREATED_BY         => 1,
                    \HostRatingType::FIELD__UPDATED_BY         => 1,
                ],
                [
                    \HostRatingType::FIELD__HOST_RATIO_ID      => 1,
                    \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                    \HostRatingType::FIELD__PROJECT_ID         => 10,
                    \HostRatingType::FIELD__CLIENT_ID          => 1,
                    \HostRatingType::FIELD__FAVORITE           => 0,
                    \HostRatingType::FIELD__CREATED_BY         => 1,
                    \HostRatingType::FIELD__UPDATED_BY         => 1,
                ],
                [
                    \HostRatingType::FIELD__HOST_RATIO_ID      => 2,
                    \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                    \HostRatingType::FIELD__PROJECT_ID         => 1,
                    \HostRatingType::FIELD__CLIENT_ID          => 1,
                    \HostRatingType::FIELD__FAVORITE           => 0,
                    \HostRatingType::FIELD__CREATED_BY         => 1,
                    \HostRatingType::FIELD__UPDATED_BY         => 1,
                ],
                [
                    \HostRatingType::FIELD__HOST_RATIO_ID      => 3,
                    \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                    \HostRatingType::FIELD__PROJECT_ID         => 2,
                    \HostRatingType::FIELD__CLIENT_ID          => 1,
                    \HostRatingType::FIELD__FAVORITE           => 0,
                    \HostRatingType::FIELD__CREATED_BY         => 1,
                    \HostRatingType::FIELD__UPDATED_BY         => 1,
                ],
                [
                    \HostRatingType::FIELD__HOST_RATIO_ID      => 2,
                    \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 1,
                    \HostRatingType::FIELD__PROJECT_ID         => 2,
                    \HostRatingType::FIELD__CLIENT_ID          => 1,
                    \HostRatingType::FIELD__FAVORITE           => 1,
                    \HostRatingType::FIELD__CREATED_BY         => 1,
                    \HostRatingType::FIELD__UPDATED_BY         => 1,
                ],
                [
                    \HostRatingType::FIELD__HOST_RATIO_ID      => 3,
                    \HostRatingType::FIELD__COMPETITOR_TYPE_ID => 2,
                    \HostRatingType::FIELD__PROJECT_ID         => 2,
                    \HostRatingType::FIELD__CLIENT_ID          => 1,
                    \HostRatingType::FIELD__FAVORITE           => 0,
                    \HostRatingType::FIELD__CREATED_BY         => 1,
                    \HostRatingType::FIELD__UPDATED_BY         => 1,
                ],
            ],
        ];
    }
    
    abstract protected function createLoader(): AbstractDataLoaderPrototype;
    
    abstract public function dataProvider(): array;
}