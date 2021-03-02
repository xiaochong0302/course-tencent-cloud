<?php

namespace App\Services\Logic;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\User as UserModel;
use App\Repos\ChapterUser as ChapterUserRepo;
use App\Validators\Chapter as ChapterValidator;

trait ChapterTrait
{

    /**
     * @var bool
     */
    protected $ownedChapter = false;

    /**
     * @var bool
     */
    protected $joinedChapter = false;

    /**
     * @var ChapterUserModel|null
     */
    protected $chapterUser;

    public function checkChapterVod($id)
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterVod($id);
    }

    public function checkChapterLive($id)
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterLive($id);
    }

    public function checkChapterRead($id)
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterRead($id);
    }

    public function checkChapter($id)
    {
        $validator = new ChapterValidator();

        return $validator->checkChapter($id);
    }

    public function checkChapterCache($id)
    {
        $validator = new ChapterValidator();

        return $validator->checkChapterCache($id);
    }

    public function setChapterUser(ChapterModel $chapter, UserModel $user)
    {
        $chapterUser = null;

        /**
         * @var CourseUserModel $courseUser
         */
        $courseUser = $this->courseUser;

        if ($user->id > 0 && $courseUser) {
            $chapterUserRepo = new ChapterUserRepo();
            $chapterUser = $chapterUserRepo->findPlanChapterUser($chapter->id, $user->id, $courseUser->plan_id);
        }

        $this->chapterUser = $chapterUser;

        if ($chapterUser) {
            $this->joinedChapter = true;
        }

        if ($this->ownedCourse || $chapter->free) {
            $this->ownedChapter = true;
        }
    }

}
