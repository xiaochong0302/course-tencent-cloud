<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Models\Question as QuestionModel;
use App\Repos\Question as QuestionRepo;
use App\Services\Search\QuestionDocument;
use App\Services\Search\QuestionSearcher;
use App\Services\Sync\QuestionIndex as QuestionIndexSync;

class SyncQuestionIndexTask extends Task
{

    public function mainAction()
    {
        $redis = $this->getRedis();

        $key = $this->getSyncKey();

        $questionIds = $redis->sRandMember($key, 1000);

        if (!$questionIds) return;

        $questionRepo = new QuestionRepo();

        $questions = $questionRepo->findByIds($questionIds);

        if ($questions->count() == 0) return;

        $document = new QuestionDocument();

        $handler = new QuestionSearcher();

        $index = $handler->getXS()->getIndex();

        $index->openBuffer();

        foreach ($questions as $question) {

            $doc = $document->setDocument($question);

            if ($question->published == QuestionModel::PUBLISH_APPROVED) {
                $index->update($doc);
            } else {
                $index->del($question->id);
            }
        }

        $index->closeBuffer();

        $redis->sRem($key, ...$questionIds);
    }

    protected function getSyncKey()
    {
        $sync = new QuestionIndexSync();

        return $sync->getSyncKey();
    }

}
