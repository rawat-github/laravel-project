<?php

namespace App\Services;

use App\Models\Test;
use ValueResearch\Scaffold\V2\Services\BaseService;

class TestService extends BaseService
{
    public function __construct(Test $model)
    {
        parent::__construct($model);
    }

// TODO: Remove this if not needed
//    public function processTopicData($bulkData, $action = 'create')
//    {
//        if($action === 'create') {
//           $this->bulkCreate($bulkData);
//        } elseif($action === 'delete') {
//           $this->bulkDelete($bulkData);
//        }
//    }

    // Add your service logic here
}
