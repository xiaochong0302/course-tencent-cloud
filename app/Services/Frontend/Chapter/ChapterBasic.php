<?php

namespace App\Services\Frontend\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Services\ChapterVod as ChapterVodService;
use App\Services\Frontend\ChapterTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Live as LiveService;
use WhichBrowser\Parser as BrowserParser;

class ChapterBasic extends FrontendService
{

    use ChapterTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapterCache($id);

        return $this->handleChapter($chapter);
    }

    protected function handleChapter(ChapterModel $chapter)
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
            'user_count' => $chapter->user_count,
            'like_count' => $chapter->like_count,
            'comment_count' => $chapter->comment_count,
        ];
    }

    protected function formatChapterLive(ChapterModel $chapter)
    {
        $headers = getallheaders();

        $browserParser = new BrowserParser($headers);

        $liveService = new LiveService();

        $stream = "chapter-{$chapter->id}";

        $format = $browserParser->isType('desktop') ? 'flv' : 'hls';

        $playUrls = $liveService->getPullUrls($stream, $format);

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
            'user_count' => $chapter->user_count,
            'like_count' => $chapter->like_count,
            'comment_count' => $chapter->comment_count,
        ];
    }

    protected function formatChapterRead(ChapterModel $chapter)
    {
        $chapterRepo = new ChapterRepo();

        $read = $chapterRepo->findChapterRead($chapter->id);

        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'summary' => $chapter->summary,
            'model' => $chapter->model,
            'content' => $read->content,
            'user_count' => $chapter->user_count,
            'like_count' => $chapter->like_count,
            'comment_count' => $chapter->comment_count,
        ];
    }

}
