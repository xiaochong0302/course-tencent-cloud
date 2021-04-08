<?php

namespace App\Services\Logic\Comment;

use App\Models\Article as ArticleModel;
use App\Models\Comment as CommentModel;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Comment as CommentValidator;

class CommentDelete extends LogicService
{

    use ArticleTrait;
    use CommentTrait;

    public function handle($id)
    {
        $comment = $this->checkComment($id);

        $user = $this->getLoginUser();

        $validator = new CommentValidator();

        $validator->checkOwner($user->id, $comment->owner_id);

        $comment->deleted = 1;

        $comment->update();

        if ($comment->item_type == CommentModel::ITEM_ARTICLE) {
            $article = $this->checkArticle($comment->item_id);
            $this->decrArticleCommentCount($article);
        }
    }

    protected function decrArticleCommentCount(ArticleModel $article)
    {
        if ($article->comment_count > 0) {
            $article->comment_count -= 1;
            $article->update();
        }
    }

}
