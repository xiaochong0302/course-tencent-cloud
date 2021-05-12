<?php

namespace App\Services\Logic\Comment;

use App\Models\Article as ArticleModel;
use App\Models\Chapter as ChapterModel;
use App\Models\Comment as CommentModel;
use App\Models\User as UserModel;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Notice\System\ArticleCommented as ArticleCommentedNotice;
use App\Services\Logic\Notice\System\ChapterCommented as ChapterCommentedNotice;
use Phalcon\Di as Di;
use Phalcon\Events\Manager as EventsManager;

trait CommentCountTrait
{

    use ArticleTrait;
    use ChapterTrait;

    protected function incrItemCommentCount(CommentModel $comment)
    {
        if ($comment->item_type == CommentModel::ITEM_CHAPTER) {

            $chapter = $this->checkChapter($comment->item_id);

            $this->incrChapterCommentCount($chapter);

            $notice = new ChapterCommentedNotice();

            $notice->handle($comment);

        } elseif ($comment->item_type == CommentModel::ITEM_ARTICLE) {

            $article = $this->checkArticle($comment->item_id);

            $this->incrArticleCommentCount($article);

            $notice = new ArticleCommentedNotice();

            $notice->handle($comment);
        }
    }

    protected function decrItemCommentCount(CommentModel $comment)
    {
        if ($comment->item_type == CommentModel::ITEM_CHAPTER) {

            $chapter = $this->checkChapter($comment->item_id);

            $this->decrChapterCommentCount($chapter);

        } elseif ($comment->item_type == CommentModel::ITEM_ARTICLE) {

            $article = $this->checkArticle($comment->item_id);

            $this->decrArticleCommentCount($article);
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

        $parent = $this->checkChapter($chapter->parent_id);

        $parent->comment_count += 1;

        $parent->update();
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

        $parent = $this->checkChapter($chapter->parent_id);

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

    protected function incrUserDailyCommentCount(UserModel $user)
    {
        /**
         * @var EventsManager $eventsManager
         */
        $eventsManager = Di::getDefault()->get('eventsManager');

        $eventsManager->fire('UserDailyCounter:incrCommentCount', $this, $user);
    }

}
