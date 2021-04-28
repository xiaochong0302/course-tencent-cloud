<?php

namespace App\Listeners;

use App\Models\Comment as CommentModel;
use Phalcon\Events\Event as PhEvent;

class Comment extends Listener
{

    public function afterCreate(PhEvent $event, $source, CommentModel $comment)
    {

    }

    public function afterUpdate(PhEvent $event, $source, CommentModel $comment)
    {

    }

    public function afterDelete(PhEvent $event, $source, CommentModel $comment)
    {

    }

    public function afterRestore(PhEvent $event, $source, CommentModel $comment)
    {

    }

    public function afterReply(PhEvent $event, $source, CommentModel $reply)
    {

    }

    public function afterLike(PhEvent $event, $source, CommentModel $comment)
    {

    }

    public function afterUndoLike(PhEvent $event, $source, CommentModel $comment)
    {

    }

}