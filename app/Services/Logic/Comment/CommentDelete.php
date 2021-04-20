<?php

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Comment as CommentValidator;

class CommentDelete extends LogicService
{

    use ArticleTrait;
    use ChapterTrait;
    use CommentTrait;
    use CommentCountTrait;

    public function handle($id)
    {
        $comment = $this->checkComment($id);

        $user = $this->getLoginUser();

        $validator = new CommentValidator();

        $validator->checkOwner($user->id, $comment->owner_id);

        $comment->deleted = 1;

        $comment->update();

        if ($comment->parent_id > 0) {

            $parent = $this->checkComment($comment->parent_id);

            $this->decrCommentReplyCount($parent);
        }

        if ($comment->item_type == CommentModel::ITEM_CHAPTER) {

            $chapter = $this->checkChapter($comment->item_id);

            $this->decrChapterCommentCount($chapter);

        } elseif ($comment->item_type == CommentModel::ITEM_ARTICLE) {

            $article = $this->checkArticle($comment->item_id);

            $this->decrArticleCommentCount($article);
        }
    }

}
