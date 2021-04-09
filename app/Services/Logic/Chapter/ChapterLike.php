<?php

namespace App\Services\Logic\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterLike as ChapterLikeModel;
use App\Models\User as UserModel;
use App\Repos\ChapterLike as ChapterLikeRepo;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Service as LogicService;
use App\Validators\UserLimit as UserLimitValidator;

class ChapterLike extends LogicService
{

    use ChapterTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapter($id);

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkDailyChapterLikeLimit($user);

        $likeRepo = new ChapterLikeRepo();

        $chapterLike = $likeRepo->findChapterLike($chapter->id, $user->id);

        if (!$chapterLike) {

            $chapterLike = new ChapterLikeModel();

            $chapterLike->chapter_id = $chapter->id;
            $chapterLike->user_id = $user->id;

            $chapterLike->create();

            $this->incrChapterLikeCount($chapter);

        } else {

            $chapterLike->delete();

            $this->decrChapterLikeCount($chapter);
        }

        $this->incrUserDailyChapterLikeCount($user);

        return $chapter->like_count;
    }

    protected function incrChapterLikeCount(ChapterModel $chapter)
    {
        $chapter->like_count += 1;

        $chapter->update();
    }

    protected function decrChapterLikeCount(ChapterModel $chapter)
    {
        if ($chapter->like_count > 0) {
            $chapter->like_count -= 1;
            $chapter->update();
        }
    }

    protected function incrUserDailyChapterLikeCount(UserModel $user)
    {
        $this->eventsManager->fire('UserDailyCounter:incrChapterLikeCount', $this, $user);
    }

}
