<?php

namespace App\Services\Frontend;

use App\Models\ChapterVote as ChapterVoteModel;
use App\Models\User as UserModel;
use App\Repos\ChapterVote as ChapterVoteRepo;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class ChapterVote extends Service
{

    use ChapterTrait;

    public function agree($id)
    {
        $chapter = $this->checkChapter($id);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkChapterVoteLimit($user->id);

        $chapterVoteRepo = new ChapterVoteRepo();

        $chapterVote = $chapterVoteRepo->findChapterVote($chapter->id, $user->id);

        if (!$chapterVote) {

            $chapterVote = new ChapterVoteModel();

            $chapterVote->chapter_id = $chapter->id;
            $chapterVote->user_id = $user->id;
            $chapterVote->type = ChapterVoteModel::TYPE_AGREE;

            $chapterVote->create();

            $chapter->agree_count += 1;

        } else {

            if ($chapterVote->type == ChapterVoteModel::TYPE_AGREE) {

                $chapterVote->type = ChapterVoteModel::TYPE_NONE;

                $chapter->agree_count -= 1;

            } elseif ($chapterVote->type == ChapterVoteModel::TYPE_OPPOSE) {

                $chapterVote->type = ChapterVoteModel::TYPE_AGREE;

                $chapter->agree_count += 1;
                $chapter->oppose_count -= 1;

            } elseif ($chapterVote->type == ChapterVoteModel::TYPE_NONE) {

                $chapterVote->type = ChapterVoteModel::TYPE_AGREE;

                $chapter->agree_count += 1;
            }

            $chapterVote->update();
        }

        $chapter->update();

        $this->incrUserDailyChapterVoteCount($user);

        return $chapter;
    }

    public function oppose($id)
    {
        $chapter = $this->checkChapter($id);

        $user = $this->getLoginUser();

        $chapterVoteRepo = new ChapterVoteRepo();

        $chapterVote = $chapterVoteRepo->findChapterVote($chapter->id, $user->id);

        if (!$chapterVote) {

            $chapterVote = new ChapterVoteModel();

            $chapterVote->chapter_id = $chapter->id;
            $chapterVote->user_id = $user->id;
            $chapterVote->type = ChapterVoteModel::TYPE_OPPOSE;

            $chapterVote->create();

            $chapter->oppose_count += 1;

        } else {

            if ($chapterVote->type == ChapterVoteModel::TYPE_AGREE) {

                $chapterVote->type = ChapterVoteModel::TYPE_OPPOSE;

                $chapter->agree_count -= 1;
                $chapter->oppose_count += 1;

            } elseif ($chapterVote->type == ChapterVoteModel::TYPE_OPPOSE) {

                $chapterVote->type = ChapterVoteModel::TYPE_NONE;

                $chapter->oppose_count -= 1;

            } elseif ($chapterVote->type == ChapterVoteModel::TYPE_NONE) {

                $chapterVote->type = ChapterVoteModel::TYPE_OPPOSE;

                $chapter->oppose_count += 1;
            }

            $chapterVote->update();
        }

        $chapter->update();

        $this->incrUserDailyChapterVoteCount($user);

        return $chapter;
    }

    protected function incrUserDailyChapterVoteCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrChapterVoteCount', $this, $user);
    }

}
