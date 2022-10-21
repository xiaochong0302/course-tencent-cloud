<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Listeners;

use App\Models\Page as PageModel;
use Phalcon\Events\Event as PhEvent;

class Page extends Listener
{

    public function afterCreate(PhEvent $event, $source, PageModel $page)
    {

    }

    public function afterUpdate(PhEvent $event, $source, PageModel $page)
    {

    }

    public function afterDelete(PhEvent $event, $source, PageModel $page)
    {

    }

    public function afterRestore(PhEvent $event, $source, PageModel $page)
    {

    }

    public function afterView(PhEvent $event, $source, PageModel $page)
    {

    }

}