<?php

namespace App\Services\Frontend\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterLike as ChapterLikeModel;
use App\Models\User as UserModel;
use App\Repos\ChapterLike as ChapterLikeRepo;
use App\Services\Frontend\ChapterTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class ChapterLike extends FrontendService
{

    use ChapterTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapter($id);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkChapterLikeLimit($user);

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

            if ($chapterLike->deleted == 0) {

                $chapterLike->update(['deleted' => 1]);

                $this->decrLikeCount($chapter);

            } else {

                $chapterLike->update(['deleted' => 0]);

                $this->incrLikeCount($chapter);
            }
        }

        $this->incrUserDailyChapterLikeCount($user);

        return $chapter;
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
        $this->eventsManager->fire('userDailyCounter:incrChapterLikeCount', $this, $user);
    }

}
