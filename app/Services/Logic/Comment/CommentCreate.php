<?php

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\Comment as CommentValidator;
use App\Validators\UserLimit as UserLimitValidator;

class CommentCreate extends LogicService
{

    use ArticleTrait;
    use ChapterTrait;
    use CommentCountTrait;

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
            'item_id' => $post['item_id'],
            'item_type' => $post['item_type'],
            'owner_id' => $user->id,
            'published' => 1,
        ];

        $data['content'] = $validator->checkContent($post['content']);

        if ($post['item_type'] == CommentModel::ITEM_CHAPTER) {

            $chapter = $this->checkChapter($post['item_id']);

            $this->incrChapterCommentCount($chapter);

        } elseif ($post['item_type'] == CommentModel::ITEM_ARTICLE) {

            $article = $this->checkArticle($post['item_id']);

            $this->incrArticleCommentCount($article);
        }

        $comment->create($data);

        $this->incrUserDailyCommentCount($user);

        return $comment;
    }

}
