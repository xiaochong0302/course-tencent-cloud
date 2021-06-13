<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
