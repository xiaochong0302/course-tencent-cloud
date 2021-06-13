<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Listeners;

use App\Models\Answer as AnswerModel;
use Phalcon\Events\Event as PhEvent;

class Answer extends Listener
{

    public function afterCreate(PhEvent $event, $source, AnswerModel $answer)
    {

    }

    public function afterUpdate(PhEvent $event, $source, AnswerModel $answer)
    {

    }

    public function afterDelete(PhEvent $event, $source, AnswerModel $answer)
    {

    }

    public function afterRestore(PhEvent $event, $source, AnswerModel $answer)
    {

    }

    public function afterLike(PhEvent $event, $source, AnswerModel $answer)
    {

    }

    public function afterUndoLike(PhEvent $event, $source, AnswerModel $answer)
    {

    }

}