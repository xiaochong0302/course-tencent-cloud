<?php

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\CommentTrait;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;
use App\Validators\Comment as CommentValidator;
use App\Validators\UserLimit as UserLimitValidator;

class CommentReply extends LogicService
{

    use ArticleTrait;
    use ChapterTrait;
    use ClientTrait;
    use CommentTrait;
    use CommentCountTrait;

    public function handle($id)
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $comment = $this->checkComment($id);

        $validator = new UserLimitValidator();

        $validator->checkDailyCommentLimit($user);

        $parent = $comment;

        $validator = new CommentValidator();

        $data = [
            'parent_id' => $parent->id,
            'item_id' => $comment->item_id,
            'item_type' => $comment->item_type,
            'owner_id' => $user->id,
            'published' => 1,
        ];

        if ($comment->parent_id > 0) {
            $parent = $validator->checkParent($comment->parent_id);
            $data['parent_id'] = $parent->id;
            $data['to_user_id'] = $comment->owner_id;
        }

        $data['content'] = $validator->checkContent($post['content']);
        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();

        $comment = new CommentModel();

        $comment->create($data);

        $this->incrCommentReplyCount($parent);

        if ($comment->item_type == CommentModel::ITEM_CHAPTER) {

            $chapter = $this->checkChapter($comment->item_id);

            $this->incrChapterCommentCount($chapter);

        } elseif ($comment->item_type == CommentModel::ITEM_ARTICLE) {

            $article = $this->checkArticle($comment->item_id);

            $this->incrArticleCommentCount($article);
        }

        $this->incrUserDailyCommentCount($user);

        return $comment;
    }

}
