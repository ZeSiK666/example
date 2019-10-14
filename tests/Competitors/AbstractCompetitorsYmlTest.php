<?php

declare(strict_types=1);

namespace Example\Tests\Competitors;

use Example\Tests\Functional\AbstractYmlFunctionalTest;

abstract class AbstractCompetitorsYmlTest extends AbstractYmlFunctionalTest
{
    protected function getClass(): string
    {
        return 'AbstractCompetitorsYmlTest';
    }
    
    protected function getDirPath(): string
    {
        return __DIR__;
    }
}