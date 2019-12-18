<?php

namespace App\Http\Admin\Services;

use App\Models\ChapterArticle as ChapterArticleModel;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Models\ChapterVideo as ChapterVideoModel;
use App\Models\Course as CourseModel;
use App\Repos\ChapterArticle as ChapterArticleRepo;
use App\Repos\ChapterLive as ChapterLiveRepo;
use App\Repos\ChapterVideo as ChapterVideoRepo;
use App\Repos\Course as CourseRepo;
use App\Validators\ChapterArticle as ChapterArticleFilter;
use App\Validators\ChapterLive as ChapterLiveFilter;
use App\Validators\ChapterVod as ChapterVideoFilter;

class ChapterContentAdmin extends Service
{

    public function get($chapter)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($chapter->course_id);

        $result = null;

        switch ($course->model) {

            case CourseModel::MODEL_VIDEO:
                $result = $this->getVideo($chapter);
                break;

            case CourseModel::MODEL_LIVE:
                $result = $this->getLive($chapter);
                break;

            case CourseModel::MODEL_ARTICLE:
                $result = $this->getArticle($chapter);
                break;
        }

        return $result;
    }

    public function create($chapter)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($chapter->course_id);

        $result = null;

        switch ($course->model) {

            case CourseModel::MODEL_VIDEO:
                $result = $this->createVideo($chapter);
                break;

            case CourseModel::MODEL_LIVE:
                $result = $this->createLive($chapter);
                break;

            case CourseModel::MODEL_ARTICLE:
                $result = $this->createArticle($chapter);
                break;
        }

        return $result;
    }

    public function update($chapter)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($chapter->course_id);

        $result = null;

        switch ($course->model) {

            case CourseModel::MODEL_VIDEO:
                $result = $this->updateVideo($chapter);
                break;

            case CourseModel::MODEL_LIVE:
                $result = $this->updateLive($chapter);
                break;

            case CourseModel::MODEL_ARTICLE:
                $result = $this->updateArticle($chapter);
                break;
        }

        return $result;
    }

    private function getVideo($chapter)
    {
        $videoRepo = new ChapterVideoRepo();

        $video = $videoRepo->findOriginChapterVideo($chapter->id);

        return $video;
    }

    private function getArticle($chapter)
    {
        $articleRepo = new ChapterArticleRepo();

        $article = $articleRepo->findByChapterId($chapter->id);

        return $article;
    }

    private function getLive($chapter)
    {
        $liveRepo = new ChapterLiveRepo();

        $live = $liveRepo->findByChapterId($chapter->id);

        $startTime = strtotime(date('y-m-d'));
        $endTime = $startTime + 3600;

        if ($live->start_time == 0) {
            $live->start_time = $startTime;
        }

        if ($live->end_time == 0) {
            $live->end_time = $endTime;
        }

        return $live;
    }

    private function createVideo($chapter)
    {
        $video = new ChapterVideoModel();

        $video->chapter_id = $chapter->id;
        $video->definition = ChapterVideoModel::DFN_ORG;

        $video->create();

        return $video;
    }

    private function createLive($chapter)
    {
        $live = new ChapterLiveModel();

        $live->chapter_id = $chapter->id;

        $live->create();

        return $live;
    }

    private function createArticle($chapter)
    {
        $article = new ChapterArticleModel();

        $article->chapter_id = $chapter->id;
        $article->content_markdown = '';
        $article->content_html = '';

        $article->create();

        return $article;
    }

    private function updateVideo($chapter)
    {
        $post = $this->request->getPost();

        $filter = new ChapterVideoFilter();

        $data = [];

        $data['play_url'] = $filter->checkPlayUrl($post['play_url']);

        $videoRepo = new ChapterVideoRepo();

        $video = $videoRepo->findOriginChapterVideo($chapter->id);

        if ($video->play_url != $data['play_url']) {
            $video->update($data);
        }

        return $video;
    }

    private function updateLive($chapter)
    {
        $post = $this->request->getPost();

        $filter = new ChapterLiveFilter();

        $data = [];

        $liveTime = $filter->checkLiveTime($post['start_time'], $post['end_time']);

        $data['start_time'] = $liveTime['start_time'];
        $data['end_time'] = $liveTime['end_time'];

        $liveRepo = new ChapterLiveRepo();

        $live = $liveRepo->findByChapterId($chapter->id);

        $live->update($data);

        return $live;
    }

    private function updateArticle($chapter)
    {
        $post = $this->request->getPost();

        $filter = new ChapterArticleFilter();

        $data = [];

        $data['content_html'] = $filter->checkContent($post['content']);

        $articleRepo = new ChapterArticleRepo();

        $article = $articleRepo->findByChapterId($chapter->id);

        $article->update($data);

        return $article;
    }

}
