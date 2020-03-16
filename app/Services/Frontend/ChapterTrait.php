<?php

namespace App\Services\Frontend;

use App\Models\Chapter as ChapterModel;
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

    public function checkChapter($id)
    {
        $validator = new ChapterValidator();

        $chapter = $validator->checkChapter($id);

        return $chapter;
    }

    /**
     * @param ChapterModel $chapter
     * @param UserModel $user
     */
    public function setChapterUser($chapter, $user)
    {
        $chapterUserRepo = new ChapterUserRepo();

        $chapterUser = $chapterUserRepo->findChapterUser($chapter->id, $user->id);

        if ($chapterUser) {
            $this->joinedChapter = true;
        }

        if ($this->ownedCourse || $chapter->free) {
            $this->ownedChapter = true;
        }
    }

}
