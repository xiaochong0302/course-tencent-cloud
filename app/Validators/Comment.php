<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Comment as CommentModel;
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

    public function checkToUser($userId)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($userId);

        if (!$user) {
            throw new BadRequestException('comment.to_user_not_found');
        }

        return $user;
    }

    public function checkItemType($itemType)
    {
        if (!array_key_exists($itemType, CommentModel::itemTypes())) {
            throw new BadRequestException('comment.invalid_item_type');
        }

        return $itemType;
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
