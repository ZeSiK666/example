<?php
declare(strict_types=1);

namespace Example\Competitors\Module;

use Example\Core\Components\Modular\AbstractContainerPrototype;

class HostTypeContainer extends AbstractContainerPrototype
{
    public const FIELD__HOST_IDS                          = 'host_ids';
    public const FIELD__PROJECT_COMPETITION_TYPE_ID       = 'project_competition_id';
    public const FIELD__HOSTS_PROJECT_TYPE_LOAD           = 'hosts_project_type_load';
    public const FIELD__HOSTS_RATING_TYPE_LOAD            = 'hosts_rating_type_load';
    public const FIELD__HOSTS_RATING_TYPE_NOT_CLIENT_LOAD = 'hosts_rating_type_not_client_load';
    public const FIELD__COMPETITOR_TYPE_LOAD              = 'competitor_type_load';
    public const FIELD__HOSTS_TYPE_DATA                   = 'hosts_type_data';
    public const FIELD__RESULT_HOSTS_TYPE_DATA            = 'result_hosts_type_data';
    //Presenter
    public const PRESENTER__HOSTS                         = 'presenter_hosts';
    public const PRESENTER__COMPETITOR_TYPES              = 'presenter_competititor_types';
    public const PRESENTER__HOSTS_TYPE_DATA               = 'presenter_hosts_type_data';
    public const PRESENTER__PROJECT_COMPETITION_TYPE_ID   = 'presenter_project_competition_id';
    public const PRESENTER__PROJECT_COMPETITION_TYPE_NAME = 'presenter_project_competition_name';
    public const PRESENTER__COMPETITOR_PROXY_HOST_IDS     = 'presenter_competitor_proxy_host_ids';
    public const PRESENTER__FILTERED_COMPETITOR_HOSTS_IDS = 'presenter_competitor_hosts_ids';
}