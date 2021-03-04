<?php

namespace App\Services\Logic\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterLike as ChapterLikeModel;
use App\Models\User as UserModel;
use App\Repos\ChapterLike as ChapterLikeRepo;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Service;
use App\Validators\UserLimit as UserLimitValidator;

class ChapterLike extends Service
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

            $chapterLike->create([
                'chapter_id' => $chapter->id,
                'user_id' => $user->id,
            ]);

            $this->incrLikeCount($chapter);

        } else {

            $chapterLike->delete();

            $this->decrLikeCount($chapter);
        }

        $this->incrUserDailyChapterLikeCount($user);

        return $chapterLike;
    }

    protected function incrLikeCount(ChapterModel $chapter)
    {
        $chapter->like_count += 1;
        $chapter->update();
    }

    protected function decrLikeCount(ChapterModel $chapter)
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
