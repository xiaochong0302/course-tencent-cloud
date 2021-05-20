<?php

namespace App\Services\Logic\Report;

use App\Models\Reason as ReasonModel;
use App\Services\Logic\Service as LogicService;

class ReasonList extends LogicService
{

    public function handle()
    {
        return ReasonModel::reportOptions();
    }

}
