<?php

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Notice\System\ArticleCommented as ArticleCommentedNotice;
use App\Services\Logic\Notice\System\ChapterCommented as ChapterCommentedNotice;
use App\Services\Logic\Point\History\CommentPost as CommentPostPointHistory;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;
use App\Validators\Comment as CommentValidator;
use App\Validators\UserLimit as UserLimitValidator;

class CommentCreate extends LogicService
{

    use ArticleTrait;
    use ChapterTrait;
    use ClientTrait;
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
        ];

        $data['content'] = $validator->checkContent($post['content']);
        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();

        /**
         * @todo 引入自动审核机制
         */
        $data['published'] = CommentModel::PUBLISH_APPROVED;

        $comment->create($data);

        $this->incrUserDailyCommentCount($user);

        $this->incrItemCommentCount($comment);

        $this->handlePostNotice($comment);

        $this->handlePostPoint($comment);

        $this->eventsManager->fire('Comment:afterCreate', $this, $comment);

        return $comment;
    }

    protected function handlePostNotice(CommentModel $comment)
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

    protected function handlePostPoint(CommentModel $comment)
    {
        if ($comment->published != CommentModel::PUBLISH_APPROVED) return;

        $service = new CommentPostPointHistory();

        $service->handle($comment);
    }

}
