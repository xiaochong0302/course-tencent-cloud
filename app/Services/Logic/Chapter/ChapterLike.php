<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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

        } else {

            $chapterLike->deleted = $chapterLike->deleted == 1 ? 0 : 1;

            $chapterLike->update();
        }

        $this->incrUserDailyChapterLikeCount($user);

        if ($chapterLike->deleted == 0) {

            $action = 'do';

            $this->incrChapterLikeCount($chapter);

            $this->eventsManager->fire('Chapter:afterLike', $this, $chapter);

        } else {

            $action = 'undo';

            $this->decrChapterLikeCount($chapter);

            $this->eventsManager->fire('Chapter:afterUndoLike', $this, $chapter);
        }

        return [
            'action' => $action,
            'count' => $chapter->like_count,
        ];
    }

    protected function incrChapterLikeCount(ChapterModel $chapter)
    {
        $chapter->like_count += 1;

        $chapter->update();

        $parent = $this->checkChapter($chapter->parent_id);

        $parent->like_count += 1;

        $parent->update();
    }

    protected function decrChapterLikeCount(ChapterModel $chapter)
    {
        if ($chapter->like_count > 0) {
            $chapter->like_count -= 1;
            $chapter->update();
        }

        $parent = $this->checkChapter($chapter->parent_id);

        if ($parent->like_count > 0) {
            $parent->like_count -= 1;
            $parent->update();
        }
    }

    protected function incrUserDailyChapterLikeCount(UserModel $user)
    {
        $this->eventsManager->fire('UserDailyCounter:incrChapterLikeCount', $this, $user);
    }

}
