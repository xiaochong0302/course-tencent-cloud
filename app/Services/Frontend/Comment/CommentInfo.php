<?php

namespace App\Services\Frontend\Comment;

use App\Models\Comment as CommentModel;
use App\Models\CommentVote as CommentVoteModel;
use App\Models\User as UserModel;
use App\Repos\CommentVote as CommentVoteRepo;
use App\Repos\User as UserRepo;
use App\Services\Frontend\CommentTrait;
use App\Services\Frontend\Service as FrontendService;

class CommentInfo extends FrontendService
{

    use CommentTrait;

    public function handle($id)
    {
        $comment = $this->checkComment($id);

        $user = $this->getCurrentUser();

        return $this->handleComment($comment, $user);
    }

    protected function handleComment(CommentModel $comment, UserModel $user)
    {
        $result = [
            'id' => $comment->id,
            'content' => $comment->content,
            'mentions' => $comment->mentions,
            'agree_count' => $comment->agree_count,
            'oppose_count' => $comment->oppose_count,
            'create_time' => $comment->create_time,
            'update_time' => $comment->update_time,
        ];

        $me = [
            'agreed' => 0,
            'opposed' => 0,
        ];

        if ($user->id > 0) {

            $voteRepo = new CommentVoteRepo();

            $vote = $voteRepo->findCommentVote($comment->id, $user->id);

            if ($vote) {
                $me['agreed'] = $vote->type == CommentVoteModel::TYPE_AGREE ? 1 : 0;
                $me['opposed'] = $vote->type == CommentVoteModel::TYPE_OPPOSE ? 1 : 0;
            }
        }

        $userRepo = new UserRepo();

        $owner = $userRepo->findById($comment->user_id);

        $result['owner'] = [
            'id' => $owner->id,
            'name' => $owner->name,
            'avatar' => $owner->avatar,
        ];

        $result['me'] = $me;

        return $result;
    }

}
