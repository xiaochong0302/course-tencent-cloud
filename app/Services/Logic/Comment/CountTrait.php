<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Comment;

use App\Models\Answer as AnswerModel;
use App\Models\Article as ArticleModel;
use App\Models\Chapter as ChapterModel;
use App\Models\Comment as CommentModel;
use App\Models\Question as QuestionModel;
use App\Models\User as UserModel;
use App\Repos\Chapter as ChapterRepo;
use Phalcon\Di as Di;
use Phalcon\Events\Manager as EventsManager;

trait CountTrait
{

    protected function incrItemCommentCount($item)
    {
        if ($item instanceof ChapterModel) {
            $this->incrChapterCommentCount($item);
        } elseif ($item instanceof ArticleModel) {
            $this->incrArticleCommentCount($item);
        } elseif ($item instanceof QuestionModel) {
            $this->incrQuestionCommentCount($item);
        } elseif ($item instanceof AnswerModel) {
            $this->incrAnswerCommentCount($item);
        }
    }

    protected function decrItemCommentCount($item)
    {
        if ($item instanceof ChapterModel) {
            $this->decrChapterCommentCount($item);
        } elseif ($item instanceof ArticleModel) {
            $this->decrArticleCommentCount($item);
        } elseif ($item instanceof QuestionModel) {
            $this->decrQuestionCommentCount($item);
        } elseif ($item instanceof AnswerModel) {
            $this->decrAnswerCommentCount($item);
        }
    }

    protected function incrCommentReplyCount(CommentModel $comment)
    {
        $comment->reply_count += 1;

        $comment->update();
    }

    protected function incrChapterCommentCount(ChapterModel $chapter)
    {
        $chapter->comment_count += 1;

        $chapter->update();

        $parent = $this->findChapter($chapter->parent_id);

        $parent->comment_count += 1;

        $parent->update();
    }

    protected function incrArticleCommentCount(ArticleModel $article)
    {
        $article->comment_count += 1;

        $article->update();
    }

    protected function incrQuestionCommentCount(QuestionModel $question)
    {
        $question->comment_count += 1;

        $question->update();
    }

    protected function incrAnswerCommentCount(AnswerModel $answer)
    {
        $answer->comment_count += 1;

        $answer->update();
    }

    protected function incrUserCommentCount(UserModel $user)
    {
        $user->comment_count += 1;

        $user->update();
    }

    protected function decrCommentReplyCount(CommentModel $comment)
    {
        if ($comment->reply_count > 0) {
            $comment->reply_count -= 1;
            $comment->update();
        }
    }

    protected function decrChapterCommentCount(ChapterModel $chapter)
    {
        if ($chapter->comment_count > 0) {
            $chapter->comment_count -= 1;
            $chapter->update();
        }

        $parent = $this->findChapter($chapter->parent_id);

        if ($parent->comment_count > 0) {
            $parent->comment_count -= 1;
            $parent->update();
        }
    }

    protected function decrArticleCommentCount(ArticleModel $article)
    {
        if ($article->comment_count > 0) {
            $article->comment_count -= 1;
            $article->update();
        }
    }

    protected function decrQuestionCommentCount(QuestionModel $question)
    {
        if ($question->comment_count > 0) {
            $question->comment_count -= 1;
            $question->update();
        }
    }

    protected function decrAnswerCommentCount(AnswerModel $answer)
    {
        if ($answer->comment_count > 0) {
            $answer->comment_count -= 1;
            $answer->update();
        }
    }

    protected function decrUserCommentCount(UserModel $user)
    {
        if ($user->comment_count > 0) {
            $user->comment_count -= 1;
            $user->update();
        }
    }

    protected function incrUserDailyCommentCount(UserModel $user)
    {
        /**
         * @var EventsManager $eventsManager
         */
        $eventsManager = Di::getDefault()->get('eventsManager');

        $eventsManager->fire('UserDailyCounter:incrCommentCount', $this, $user);
    }

    protected function findChapter($id)
    {
        $chapterRepo = new ChapterRepo();

        return $chapterRepo->findById($id);
    }

}
