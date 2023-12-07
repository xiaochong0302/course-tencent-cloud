<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.com
 */

namespace App\Console\Migrations;

use App\Models\ChapterUser as ChapterUserModel;
use App\Models\CourseUser as CourseUserModel;
use Phalcon\Mvc\Model\ResultsetInterface;

class V20231201101515 extends Migration
{

    public function run()
    {
        $this->handleCourseUsers();
        $this->handleChapterUsers();
    }

    protected function handleCourseUsers()
    {
        $courseUsers = $this->findCourseUsers();

        if ($courseUsers->count() == 0) return;

        $mappings = [];

        /**
         * 只保留第一条记录
         */
        foreach ($courseUsers as $courseUser) {
            $key = $courseUser->course_id . '-' . $courseUser->user_id;
            if (!isset($mappings[$key])) {
                $mappings[$key] = 1;
            } else {
                $courseUser->deleted = 1;
                $courseUser->update();
            }
        }
    }

    protected function handleChapterUsers()
    {
        $chapterUsers = $this->findChapterUsers();

        if ($chapterUsers->count() == 0) return;

        /**
         * 只保留第一条记录
         */
        foreach ($chapterUsers as $chapterUser) {
            $key = $chapterUser->chapter_id . '-' . $chapterUser->user_id;
            if (!isset($mappings[$key])) {
                $mappings[$key] = 1;
            } else {
                $chapterUser->deleted = 1;
                $chapterUser->update();
            }
        }
    }

    /**
     * @return ResultsetInterface|CourseUserModel[]
     */
    protected function findCourseUsers()
    {
        return CourseUserModel::query()
            ->where('deleted = 0')
            ->orderBy('id DESC')
            ->execute();
    }

    /**
     * @return ResultsetInterface|ChapterUserModel[]
     */
    protected function findChapterUsers()
    {
        return ChapterUserModel::query()
            ->where('deleted = 0')
            ->orderBy('id DESC')
            ->execute();
    }

}