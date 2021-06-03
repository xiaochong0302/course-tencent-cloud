<?php

namespace App\Services\Logic\Comment;

use App\Models\Answer as AnswerModel;
use App\Models\Article as ArticleModel;
use App\Models\Chapter as ChapterModel;
use App\Models\Comment as CommentModel;
use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use App\Services\Logic\Notice\System\AnswerCommented as AnswerCommentedNotice;
use App\Services\Logic\Notice\System\ArticleCommented as ArticleCommentedNotice;
use App\Services\Logic\Notice\System\QuestionCommented as QuestionCommentedNotice;
use App\Services\Logic\Point\History\CommentPost as CommentPostPointHistory;

trait AfterCreateTrait
{

    use CountTrait;

    protected function incrItemCommentCount($item, CommentModel $comment, UserModel $user)
    {
        if ($comment->published != CommentModel::PUBLISH_APPROVED) return;

        if ($item instanceof ChapterModel) {
            $this->incrChapterCommentCount($item);
        } elseif ($item instanceof ArticleModel) {
            $this->incrArticleCommentCount($item);
        } elseif ($item instanceof QuestionModel) {
            $this->incrQuestionCommentCount($item);
        } elseif ($item instanceof AnswerModel) {
            $this->incrAnswerCommentCount($item);
        }

        $this->incrUserCommentCount($user);
    }

    protected function handleNoticeAndPoint($item, CommentModel $comment, UserModel $user)
    {
        if ($comment->published != CommentModel::PUBLISH_APPROVED) return;

        if ($item instanceof ArticleModel) {
            if ($user->id != $item->owner_id) {
                $this->handleArticleCommentedNotice($item, $comment);
                $this->handleCommentPostPoint($comment);
            }
        } elseif ($item instanceof QuestionModel) {
            if ($user->id != $item->owner_id) {
                $this->handleQuestionCommentedNotice($item, $comment);
                $this->handleCommentPostPoint($comment);
            }
        } elseif ($item instanceof AnswerModel) {
            if ($user->id != $item->owner_id) {
                $this->handleAnswerCommentedNotice($item, $comment);
                $this->handleCommentPostPoint($comment);
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

    protected function handleCommentPostPoint(CommentModel $comment)
    {
        if ($comment->published != CommentModel::PUBLISH_APPROVED) return;

        $service = new CommentPostPointHistory();

        $service->handle($comment);
    }

}
