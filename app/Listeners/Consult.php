<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Listeners;

use App\Models\Consult as ConsultModel;
use Phalcon\Events\Event as PhEvent;

class Consult extends Listener
{

    public function afterCreate(PhEvent $event, $source, ConsultModel $consult)
    {

    }

    public function afterUpdate(PhEvent $event, $source, ConsultModel $consult)
    {

    }

    public function afterDelete(PhEvent $event, $source, ConsultModel $consult)
    {

    }

    public function afterRestore(PhEvent $event, $source, ConsultModel $consult)
    {

    }

    public function afterApprove(PhEvent $event, $source, ConsultModel $consult)
    {

    }

    public function afterReject(PhEvent $event, $source, ConsultModel $consult)
    {

    }

    public function afterReply(PhEvent $event, $source, ConsultModel $consult)
    {

    }

    public function afterLike(PhEvent $event, $source, ConsultModel $consult)
    {

    }

    public function afterUndoLike(PhEvent $event, $source, ConsultModel $consult)
    {

    }

}