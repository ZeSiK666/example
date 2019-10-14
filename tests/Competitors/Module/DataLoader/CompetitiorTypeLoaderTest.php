<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Module\DataLoader;

use Example\Competitors\Module\DataLoader\CompetitiorTypeLoader;
use Example\Database\DatabaseConstants;
use Example\Tests\Competitors\AbstractCompetitorsYmlTest;

class CompetitiorTypeLoaderTest extends AbstractCompetitorsYmlTest
{
    /**
     * @var CompetitiorTypeLoader
     */
    private $loader;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        \Y::dbCmd(
            sprintf(
                'DELETE FROM %s; ALTER SEQUENCE dictionary.host_id_seq RESTART WITH 1;',
                \CompetitorType::TABLE_NAME
            ),
            \CompetitorType::DATABASE_CONNECTOR_NAME
        )->execute();
        
        $this->loader = new CompetitiorTypeLoader();
        
        $this->loadData();
    }
    
    protected function tearDown(): void
    {
        parent::tearDown();
        
        $this->loader = null;
    }
    
    private function loadData(): void
    {
        \Y::massDataInsert(
            \Y::dbOriginal(DatabaseConstants::DATABASE_CONNECTION_NAME_PG_FAST),
            \CompetitorType::TABLE_NAME,
            $this->data()
        );
    }
    
    /**
     * @param array $expected
     *
     * @dataProvider dataProvider
     */
    public function testPrepare(array $expected): void
    {
        $this->loader->prepare();
        
        static::assertSame($expected, $this->loader->getData());
    }
    
    public function dataProvider(): array
    {
        return [
            [
                [
                    [
                        \CompetitorType::FIELD__ID   => 1,
                        \CompetitorType::FIELD__NAME => 'type_1',
                    ],
                    [
                        \CompetitorType::FIELD__ID   => 2,
                        \CompetitorType::FIELD__NAME => 'type_2',
                    ],
                    [
                        \CompetitorType::FIELD__ID   => 3,
                        \CompetitorType::FIELD__NAME => 'type_n',
                    ],
                ],
            ],
        ];
    }
    
    private function data(): array
    {
        return [
            [
                \CompetitorType::FIELD__ID   => 1,
                \CompetitorType::FIELD__NAME => 'type_1',
            ],
            [
                \CompetitorType::FIELD__ID   => 2,
                \CompetitorType::FIELD__NAME => 'type_2',
            ],
            [
                \CompetitorType::FIELD__ID   => 3,
                \CompetitorType::FIELD__NAME => 'type_n',
            ],
        ];
    }
}