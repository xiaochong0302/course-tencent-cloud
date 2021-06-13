<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Report;

use App\Models\Answer as AnswerModel;
use App\Models\Article as ArticleModel;
use App\Models\Comment as CommentModel;
use App\Models\Question as QuestionModel;

trait CountTrait
{

    protected function handleItemReportCount($item)
    {
        if ($item instanceof ArticleModel) {
            $this->incrArticleReportCount($item);
        } elseif ($item instanceof QuestionModel) {
            $this->incrQuestionReportCount($item);
        } elseif ($item instanceof AnswerModel) {
            $this->incrAnswerReportCount($item);
        } elseif ($item instanceof CommentModel) {
            $this->incrCommentReportCount($item);
        }
    }

    protected function incrArticleReportCount(ArticleModel $article)
    {
        $article->report_count += 1;

        $article->update();
    }

    protected function incrQuestionReportCount(QuestionModel $question)
    {
        $question->report_count += 1;

        $question->update();
    }

    protected function incrAnswerReportCount(AnswerModel $answer)
    {
        $answer->report_count += 1;

        $answer->update();
    }

    protected function incrCommentReportCount(CommentModel $comment)
    {
        $comment->report_count += 1;

        $comment->update();
    }

}
