<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Listeners;

use App\Models\Question as QuestionModel;
use Phalcon\Events\Event as PhEvent;

class Question extends Listener
{

    public function afterCreate(PhEvent $event, $source, QuestionModel $question)
    {

    }

    public function afterUpdate(PhEvent $event, $source, QuestionModel $question)
    {

    }

    public function afterDelete(PhEvent $event, $source, QuestionModel $question)
    {

    }

    public function afterRestore(PhEvent $event, $source, QuestionModel $question)
    {

    }

    public function afterApprove(PhEvent $event, $source, QuestionModel $question)
    {

    }

    public function afterReject(PhEvent $event, $source, QuestionModel $question)
    {

    }

    public function afterView(PhEvent $event, $source, QuestionModel $question)
    {

    }

    public function afterFavorite(PhEvent $event, $source, QuestionModel $question)
    {

    }

    public function afterUndoFavorite(PhEvent $event, $source, QuestionModel $question)
    {

    }

    public function afterLike(PhEvent $event, $source, QuestionModel $question)
    {

    }

    public function afterUndoLike(PhEvent $event, $source, QuestionModel $question)
    {

    }

}