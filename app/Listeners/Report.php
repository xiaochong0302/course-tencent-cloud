<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Listeners;

use App\Models\Report as ReportModel;
use Phalcon\Events\Event as PhEvent;

class Report extends Listener
{

    public function afterCreate(PhEvent $event, $source, ReportModel $report)
    {

    }

    public function afterUpdate(PhEvent $event, $source, ReportModel $report)
    {

    }

    public function afterDelete(PhEvent $event, $source, ReportModel $report)
    {

    }

}