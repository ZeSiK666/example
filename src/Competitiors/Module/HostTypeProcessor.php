<?php
declare(strict_types=1);

namespace Example\Competitors\Module;

use Example\Core\Components\Modular\AbstractProcessorPrototype;
use Example\Core\Components\Modular\Profiling\Profiler;

class HostTypeProcessor extends AbstractProcessorPrototype
{
    public function prepare(): void
    {
        $this->profiler = new Profiler();
    }
}