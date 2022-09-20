<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Comment;

use App\Models\Answer as AnswerModel;
use App\Models\Article as ArticleModel;
use App\Models\Comment as CommentModel;
use App\Models\Question as QuestionModel;
use App\Services\Logic\Notice\Internal\AnswerCommented as AnswerCommentedNotice;
use App\Services\Logic\Notice\Internal\ArticleCommented as ArticleCommentedNotice;
use App\Services\Logic\Notice\Internal\CommentReplied as CommentRepliedNotice;
use App\Services\Logic\Notice\Internal\QuestionCommented as QuestionCommentedNotice;
use App\Services\Logic\Point\History\CommentPost as CommentPostPointHistory;

trait AfterCreateTrait
{

    use CountTrait;

    protected function handleItemCommentedNotice($item, CommentModel $comment)
    {
        if ($item instanceof ArticleModel) {
            if ($comment->owner_id != $item->owner_id) {
                $this->handleArticleCommentedNotice($item, $comment);
            }
        } elseif ($item instanceof QuestionModel) {
            if ($comment->owner_id != $item->owner_id) {
                $this->handleQuestionCommentedNotice($item, $comment);
            }
        } elseif ($item instanceof AnswerModel) {
            if ($comment->owner_id != $item->owner_id) {
                $this->handleAnswerCommentedNotice($item, $comment);
            }
        }
    }

    protected function handleArticleCommentedNotice(ArticleModel $article, CommentModel $comment)
    {
        $notice = new ArticleCommentedNotice();

        $notice->handle($article, $comment);
    }

    protected function handleQuestionCommentedNotice(QuestionModel $question, CommentModel $comment)
    {
        $notice = new QuestionCommentedNotice();

        $notice->handle($question, $comment);
    }

    protected function handleAnswerCommentedNotice(AnswerModel $answer, CommentModel $comment)
    {
        $notice = new AnswerCommentedNotice();

        $notice->handle($answer, $comment);
    }

    protected function handleCommentRepliedNotice(CommentModel $reply)
    {
        $notice = new CommentRepliedNotice();

        $notice->handle($reply);
    }

    protected function handleCommentPostPoint(CommentModel $comment)
    {
        if ($comment->published != CommentModel::PUBLISH_APPROVED) return;

        $service = new CommentPostPointHistory();

        $service->handle($comment);
    }

}
