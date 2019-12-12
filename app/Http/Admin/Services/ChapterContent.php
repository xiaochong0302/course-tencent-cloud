<?php

namespace App\Http\Admin\Services;

use App\Library\Util\Word as WordUtil;
use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Services\CourseStats as CourseStatsService;
use App\Services\Vod as VodService;
use App\Validators\ChapterArticle as ChapterArticleValidator;
use App\Validators\ChapterLive as ChapterLiveValidator;
use App\Validators\ChapterVod as ChapterVodValidator;

class ChapterContent extends Service
{

    public function getChapterVod($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        $result = $chapterRepo->findChapterVod($chapterId);

        return $result;
    }

    public function getChapterLive($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        $result = $chapterRepo->findChapterLive($chapterId);

        return $result;
    }

    public function getChapterArticle($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        $result = $chapterRepo->findChapterArticle($chapterId);

        return $result;
    }

    public function getTranslatedFiles($fileId)
    {
        if (!$fileId) return;

        $vodService = new VodService();

        $mediaInfo = $vodService->getMediaInfo($fileId);

        if (!$mediaInfo) return;

        $result = [];

        $files = $mediaInfo['MediaInfoSet'][0]['TranscodeInfo']['TranscodeSet'];

        foreach ($files as $file) {

            if ($file['Definition'] == 0) {
                continue;
            }

            $result[] = [
                'play_url' => $vodService->getPlayUrl($file['Url']),
                'width' => $file['Width'],
                'height' => $file['Height'],
                'definition' => $file['Definition'],
                'duration' => kg_play_duration($file['Duration']),
                'format' => pathinfo($file['Url'], PATHINFO_EXTENSION),
                'size' => sprintf('%0.2f', $file['Size'] / 1024 / 1024),
                'bit_rate' => intval($file['Bitrate'] / 1024),
            ];
        }

        return kg_array_object($result);
    }

    public function updateChapterContent($chapterId)
    {
        $chapterRepo = new ChapterRepo();
        $chapter = $chapterRepo->findById($chapterId);

        $courseRepo = new CourseRepo();
        $course = $courseRepo->findById($chapter->course_id);

        switch ($course->model) {
            case CourseModel::MODEL_VOD:
                $this->updateChapterVod($chapter);
                break;
            case CourseModel::MODEL_LIVE:
                $this->updateChapterLive($chapter);
                break;
            case CourseModel::MODEL_ARTICLE:
                $this->updateChapterArticle($chapter);
                break;
        }
    }

    protected function updateChapterVod($chapter)
    {
        $post = $this->request->getPost();

        $validator = new ChapterVodValidator();

        $fileId = $validator->checkFileId($post['file_id']);

        $chapterRepo = new ChapterRepo();

        $vod = $chapterRepo->findChapterVod($chapter->id);

        if ($fileId == $vod->file_id) {
            return;
        }

        $vod->update(['file_id' => $fileId]);

        $attrs = $chapter->attrs;
        $attrs->file_id = $fileId;
        $attrs->file_status = ChapterModel::FS_UPLOADED;
        $chapter->update(['attrs' => $attrs]);
    }

    protected function updateChapterLive($chapter)
    {
        $post = $this->request->getPost();

        $chapterRepo = new ChapterRepo();

        $live = $chapterRepo->findChapterLive($chapter->id);

        $validator = new ChapterLiveValidator();

        $data = [];

        $data['start_time'] = $validator->checkStartTime($post['start_time']);
        $data['end_time'] = $validator->checkEndTime($post['end_time']);

        $validator->checkTimeRange($post['start_time'], $post['end_time']);

        $live->update($data);

        $attrs = $chapter->attrs;
        $attrs->start_time = $data['start_time'];
        $attrs->end_time = $data['end_time'];
        $chapter->update(['attrs' => $attrs]);

        $courseStats = new CourseStatsService();
        $courseStats->updateLiveDateRange($chapter->course_id);
    }

    protected function updateChapterArticle($chapter)
    {
        $post = $this->request->getPost();

        $chapterRepo = new ChapterRepo();

        $article = $chapterRepo->findChapterArticle($chapter->id);

        $validator = new ChapterArticleValidator();

        $data = [];

        $data['content'] = $validator->checkContent($post['content']);

        $article->update($data);

        $attrs = $chapter->attrs;
        $attrs->word_count = WordUtil::getWordCount($article->content);
        $chapter->update(['attrs' => $attrs]);

        $courseStats = new CourseStatsService();
        $courseStats->updateArticleWordCount($chapter->course_id);
    }

}
