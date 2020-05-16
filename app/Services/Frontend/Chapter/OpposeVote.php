<?php

namespace App\Services\Frontend\Chapter;

use App\Models\ChapterVote as ChapterVoteModel;
use App\Repos\ChapterVote as ChapterVoteRepo;
use App\Services\Frontend\ChapterTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class OpposeVote extends FrontendService
{

    use ChapterTrait, VoteTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapter($id);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkChapterVoteLimit($user);

        $chapterVoteRepo = new ChapterVoteRepo();

        $chapterVote = $chapterVoteRepo->findChapterVote($chapter->id, $user->id);

        if (!$chapterVote) {

            $chapterVote = new ChapterVoteModel();

            $chapterVote->chapter_id = $chapter->id;
            $chapterVote->user_id = $user->id;
            $chapterVote->type = ChapterVoteModel::TYPE_OPPOSE;

            $chapterVote->create();

            $this->incrOpposeCount($chapter);

        } else {

            if ($chapterVote->type == ChapterVoteModel::TYPE_AGREE) {

                $chapterVote->type = ChapterVoteModel::TYPE_OPPOSE;

                $this->decrAgreeCount($chapter);

                $this->incrOpposeCount($chapter);

            } elseif ($chapterVote->type == ChapterVoteModel::TYPE_OPPOSE) {

                $chapterVote->type = ChapterVoteModel::TYPE_NONE;

                $this->decrOpposeCount($chapter);

            } elseif ($chapterVote->type == ChapterVoteModel::TYPE_NONE) {

                $chapterVote->type = ChapterVoteModel::TYPE_OPPOSE;

                $this->incrOpposeCount($chapter);
            }

            $chapterVote->update();
        }

        $this->incrUserDailyChapterVoteCount($user);

        return $chapter;
    }

}
