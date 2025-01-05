<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\User as UserModel;
use App\Repos\ChapterUser as ChapterUserRepo;

trait ChapterUserTrait
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

    public function setChapterUser(ChapterModel $chapter, UserModel $user)
    {
        if ($user->id == 0) return;

        $chapterUser = null;

        $courseUser = $this->courseUser;

        if ($courseUser) {
            $chapterUserRepo = new ChapterUserRepo();
            $chapterUser = $chapterUserRepo->findChapterUser($chapter->id, $user->id);
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
