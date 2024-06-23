<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Comment as CommentModel;
use App\Models\Reason as ReasonModel;
use App\Repos\Comment as CommentRepo;
use App\Repos\User as UserRepo;

class Comment extends Validator
{

    public function checkComment($id)
    {
        $commentRepo = new CommentRepo();

        $comment = $commentRepo->findById($id);

        if (!$comment) {
            throw new BadRequestException('comment.not_found');
        }

        return $comment;
    }

    public function checkParent($id)
    {
        $commentRepo = new CommentRepo();

        $comment = $commentRepo->findById($id);

        if (!$comment) {
            throw new BadRequestException('comment.parent_not_found');
        }

        return $comment;
    }

    public function checkItem($itemId, $itemType)
    {
        if (!array_key_exists($itemType, CommentModel::itemTypes())) {
            throw new BadRequestException('comment.invalid_item_type');
        }

        $result = null;

        switch ($itemType) {
            case CommentModel::ITEM_CHAPTER:
                $validator = new Chapter();
                $result = $validator->checkChapter($itemId);
                break;
            case CommentModel::ITEM_ARTICLE:
                $validator = new Article();
                $result = $validator->checkArticle($itemId);
                break;
            case CommentModel::ITEM_QUESTION:
                $validator = new Question();
                $result = $validator->checkQuestion($itemId);
                break;
            case CommentModel::ITEM_ANSWER:
                $validator = new Answer();
                $result = $validator->checkAnswer($itemId);
                break;
        }

        return $result;
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 1) {
            throw new BadRequestException('comment.content_too_short');
        }

        if ($length > 1000) {
            throw new BadRequestException('comment.content_too_long');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        if (!array_key_exists($status, CommentModel::publishTypes())) {
            throw new BadRequestException('comment.invalid_publish_status');
        }

        return $status;
    }

}
