<?php

namespace App\Services\Logic\Comment;

use App\Models\Article as ArticleModel;
use App\Models\Comment as CommentModel;
use App\Models\User as UserModel;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Comment as CommentValidator;
use App\Validators\UserLimit as UserLimitValidator;

class CommentCreate extends LogicService
{

    use ArticleTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyCommentLimit($user);

        $validator = new CommentValidator();

        $validator->checkItemType($post['item_type']);

        $comment = new CommentModel();

        $data = [
            'owner_id' => $user->id,
            'published' => 1,
        ];

        $data['content'] = $validator->checkContent($post['content']);

        if (isset($post['to_user_id'])) {
            $toUser = $validator->checkToUser($post['to_user_id']);
            $data['to_user_id'] = $toUser->id;
        }

        if ($post['item_type'] == CommentModel::ITEM_ARTICLE) {
            $article = $this->checkArticle($post['item_id']);
            $this->incrArticleCommentCount($article);
        }

        $comment->create($data);

        $this->incrUserDailyCommentCount($user);

        return $comment;
    }

    protected function incrArticleCommentCount(ArticleModel $article)
    {
        $article->comment_count += 1;

        $article->update();
    }

    protected function incrUserDailyCommentCount(UserModel $user)
    {
        $this->eventsManager->fire('UserDailyCounter:incrCommentCount', $this, $user);
    }

}
