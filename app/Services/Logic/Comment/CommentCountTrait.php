<?php

namespace App\Services\Logic\Comment;

use App\Models\Article as ArticleModel;
use App\Models\Chapter as ChapterModel;
use App\Models\Comment as CommentModel;
use App\Models\User as UserModel;
use Phalcon\Di as Di;
use Phalcon\Events\Manager as EventsManager;

trait CommentCountTrait
{

    protected function incrCommentReplyCount(CommentModel $comment)
    {
        $comment->reply_count += 1;

        $comment->update();
    }

    protected function incrChapterCommentCount(ChapterModel $chapter)
    {
        $chapter->comment_count += 1;

        $chapter->update();
    }

    protected function incrArticleCommentCount(ArticleModel $article)
    {
        $article->comment_count += 1;

        $article->update();
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
    }

    protected function decrArticleCommentCount(ArticleModel $article)
    {
        if ($article->comment_count > 0) {
            $article->comment_count -= 1;
            $article->update();
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

}
