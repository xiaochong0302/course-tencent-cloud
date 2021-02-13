<?php

namespace App\Services\Logic\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Services\ChapterVod as ChapterVodService;
use App\Services\Live as LiveService;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service;

class BasicInfo extends Service
{

    use CourseTrait;
    use ChapterTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapter($id);

        $course = $this->checkCourse($chapter->course_id);

        $result = $this->handleBasicInfo($chapter);

        $result['course'] = $this->handleCourseInfo($course);

        return $result;
    }

    public function handleBasicInfo(ChapterModel $chapter)
    {
        $result = [];

        switch ($chapter->model) {
            case CourseModel::MODEL_VOD:
                $result = $this->formatChapterVod($chapter);
                break;
            case CourseModel::MODEL_LIVE:
                $result = $this->formatChapterLive($chapter);
                break;
            case CourseModel::MODEL_READ:
                $result = $this->formatChapterRead($chapter);
                break;
        }

        return $result;
    }

    public function handleCourseInfo(CourseModel $course)
    {
        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
        ];
    }

    protected function formatChapterVod(ChapterModel $chapter)
    {
        $chapterVodService = new ChapterVodService();

        $playUrls = $chapterVodService->getPlayUrls($chapter->id);

        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'summary' => $chapter->summary,
            'model' => $chapter->model,
            'play_urls' => $playUrls,
            'resource_count' => $chapter->resource_count,
            'consult_count' => $chapter->consult_count,
            'user_count' => $chapter->user_count,
            'like_count' => $chapter->like_count,
        ];
    }

    protected function formatChapterLive(ChapterModel $chapter)
    {
        $liveService = new LiveService();

        $streamName = ChapterLiveModel::generateStreamName($chapter->id);

        $playUrls = $liveService->getPullUrls($streamName);

        $chapterRepo = new ChapterRepo();

        $live = $chapterRepo->findChapterLive($chapter->id);

        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'summary' => $chapter->summary,
            'model' => $chapter->model,
            'play_urls' => $playUrls,
            'start_time' => $live->start_time,
            'end_time' => $live->end_time,
            'status' => $live->status,
            'resource_count' => $chapter->resource_count,
            'consult_count' => $chapter->consult_count,
            'user_count' => $chapter->user_count,
            'like_count' => $chapter->like_count,
        ];
    }

    protected function formatChapterRead(ChapterModel $chapter)
    {
        $chapterRepo = new ChapterRepo();

        $read = $chapterRepo->findChapterRead($chapter->id);

        $read->content = kg_parse_markdown($read->content);

        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'summary' => $chapter->summary,
            'model' => $chapter->model,
            'content' => $read->content,
            'resource_count' => $chapter->resource_count,
            'consult_count' => $chapter->consult_count,
            'user_count' => $chapter->user_count,
            'like_count' => $chapter->like_count,
        ];
    }

}
