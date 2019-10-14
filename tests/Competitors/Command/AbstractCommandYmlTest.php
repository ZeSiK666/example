<?php

declare(strict_types=1);

namespace Example\Tests\Competitors\Command;

use Example\Database\DatabaseConstants;
use Example\Tests\Competitors\AbstractCompetitorsYmlTest;

abstract class AbstractCommandYmlTest extends AbstractCompetitorsYmlTest
{
    protected function setUp(): void
    {
        parent::setUp();
        
        \Y::dbCmd(
            sprintf(
                'DELETE FROM %s; ALTER SEQUENCE competition.host_ratio_id_seq RESTART WITH 1;',
                \HostRatio::TABLE_NAME
            ),
            \HostRatingType::DATABASE_CONNECTOR_NAME
        )->execute();
        
        $this->loadData();
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
    
    private function data(): array
    {
        return [
            \HostRatio::TABLE_NAME => [
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
        ];
    }
}