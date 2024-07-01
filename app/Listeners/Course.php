<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Listeners;

use App\Models\Course as CourseModel;
use Phalcon\Events\Event as PhEvent;

class Course extends Listener
{

    public function afterCreate(PhEvent $event, $source, CourseModel $course): void
    {

    }

    public function afterUpdate(PhEvent $event, $source, CourseModel $course): void
    {

    }

    public function afterDelete(PhEvent $event, $source, CourseModel $course): void
    {

    }

    public function afterRestore(PhEvent $event, $source, CourseModel $course): void
    {

    }

    public function afterView(PhEvent $event, $source, CourseModel $course): void
    {

    }

    public function afterFavorite(PhEvent $event, $source, CourseModel $course): void
    {

    }

    public function afterUndoFavorite(PhEvent $event, $source, CourseModel $course): void
    {

    }

}