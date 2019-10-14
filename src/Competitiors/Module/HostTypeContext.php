<?php
declare(strict_types=1);

namespace Example\Competitors\Module;

use Example\Core\Components\Modular\AbstractContextPrototype;

class HostTypeContext extends AbstractContextPrototype
{
    public const FIELD__PROJECT_ID      = 'project_id';
    public const FIELD__CLIENT_ID       = 'client_id';
    public const FIELD__FILTER_FAVORITE = 'filter_favorite';
    public const FIELD__FILTER_TYPE_ID  = 'filter_type_id';
    public const FIELD__FILTER_EQUAL    = 'filter_equal';
}