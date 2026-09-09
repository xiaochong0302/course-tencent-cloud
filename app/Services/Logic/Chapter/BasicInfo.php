<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Services\ChapterVod as ChapterVodService;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\ContentTrait;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class BasicInfo extends LogicService
{

    use CourseTrait;
    use ChapterTrait;
    use ContentTrait;

    public function handle(int $id): array
    {
        $chapter = $this->checkChapter($id);

        $course = $this->checkCourse($chapter->course_id);

        $result = $this->handleBasicInfo($chapter);

        $result['course'] = $this->handleCourseInfo($course);

        return $result;
    }

    public function handleBasicInfo(ChapterModel $chapter): array
    {
        $result = [];

        switch ($chapter->model) {
            case CourseModel::MODEL_VOD:
                $result = $this->formatChapterVod($chapter);
                break;
            case CourseModel::MODEL_READ:
                $result = $this->formatChapterRead($chapter);
                break;
        }

        return $result;
    }

    public function handleCourseInfo(CourseModel $course): array
    {
        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
        ];
    }

    protected function formatChapterVod(ChapterModel $chapter): array
    {
        $chapterRepo = new ChapterRepo();

        $vod = $chapterRepo->findChapterVod($chapter->id);

        $chapterVodService = new ChapterVodService();

        $playUrls = $chapterVodService->getPlayUrls($vod);

        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'summary' => $chapter->summary,
            'model' => $chapter->model,
            'settings' => $vod->settings,
            'published' => $chapter->published,
            'deleted' => $chapter->deleted,
            'comment_count' => $chapter->comment_count,
            'user_count' => $chapter->user_count,
            'like_count' => $chapter->like_count,
            'create_time' => $chapter->create_time,
            'update_time' => $chapter->update_time,
            'play_urls' => $playUrls,
        ];
    }

    protected function formatChapterRead(ChapterModel $chapter): array
    {
        $chapterRepo = new ChapterRepo();

        $read = $chapterRepo->findChapterRead($chapter->id);

        $content = kg_parse_markdown($read->content);
        $content = $this->handleContent($content);

        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'summary' => $chapter->summary,
            'model' => $chapter->model,
            'content' => $content,
            'settings' => $read->settings,
            'published' => $chapter->published,
            'deleted' => $chapter->deleted,
            'comment_count' => $chapter->comment_count,
            'user_count' => $chapter->user_count,
            'like_count' => $chapter->like_count,
            'create_time' => $chapter->create_time,
            'update_time' => $chapter->update_time,
        ];
    }

}
