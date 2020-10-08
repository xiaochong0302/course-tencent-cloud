<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Chapter as ChapterService;
use App\Http\Admin\Services\ChapterContent as ChapterContentService;
use App\Http\Admin\Services\Course as CourseService;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Models\Course as CourseModel;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/admin/chapter")
 */
class ChapterController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/resources", name="admin.chapter.resources")
     */
    public function resourcesAction($id)
    {
        $chapterService = new ChapterService();

        $resources = $chapterService->getResources($id);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        $this->view->setVar('resources', $resources);
    }

    /**
     * @Get("/{id:[0-9]+}/lessons", name="admin.chapter.lessons")
     */
    public function lessonsAction($id)
    {
        $courseService = new CourseService();
        $chapterService = new ChapterService();

        $chapter = $chapterService->getChapter($id);
        $lessons = $chapterService->getLessons($chapter->id);
        $course = $courseService->getCourse($chapter->course_id);

        $this->view->setVar('chapter', $chapter);
        $this->view->setVar('lessons', $lessons);
        $this->view->setVar('course', $course);
    }

    /**
     * @Get("/add", name="admin.chapter.add")
     */
    public function addAction()
    {
        $courseId = $this->request->getQuery('course_id', 'int');
        $parentId = $this->request->getQuery('parent_id', 'int');
        $type = $this->request->getQuery('type', 'string', 'chapter');

        $courseService = new CourseService();

        $course = $courseService->getCourse($courseId);
        $chapters = $courseService->getChapters($courseId);

        $this->view->pick('chapter/add_chapter');

        if ($type == 'lesson') {
            $this->view->pick('chapter/add_lesson');
        }

        $this->view->setVar('course', $course);
        $this->view->setVar('parent_id', $parentId);
        $this->view->setVar('chapters', $chapters);
    }

    /**
     * @Post("/create", name="admin.chapter.create")
     */
    public function createAction()
    {
        $chapterService = new ChapterService();

        $chapter = $chapterService->createChapter();

        $location = $this->url->get([
            'for' => 'admin.course.chapters',
            'id' => $chapter->course_id,
        ]);

        $content = [
            'location' => $location,
            'msg' => '创建章节成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.chapter.edit")
     */
    public function editAction($id)
    {
        $contentService = new ChapterContentService();
        $chapterService = new ChapterService();
        $courseService = new CourseService();

        $chapter = $chapterService->getChapter($id);
        $course = $courseService->getCourse($chapter->course_id);

        $this->view->pick('chapter/edit_chapter');

        if ($chapter->parent_id > 0) {

            $this->view->pick('chapter/edit_lesson');

            $resources = $chapterService->getResources($chapter->id);

            $cos = $chapterService->getSettings('cos');

            $this->view->setVar('cos', $cos);

            switch ($course->model) {
                case CourseModel::MODEL_VOD:
                    $vod = $contentService->getChapterVod($chapter->id);
                    $playUrls = $contentService->getPlayUrls($chapter->id);
                    $this->view->setVar('vod', $vod);
                    $this->view->setVar('play_urls', $playUrls);
                    break;
                case CourseModel::MODEL_LIVE:
                    $live = $contentService->getChapterLive($chapter->id);
                    $streamName = ChapterLiveModel::generateStreamName($chapter->id);
                    $this->view->setVar('live', $live);
                    $this->view->setVar('stream_name', $streamName);
                    break;
                case CourseModel::MODEL_READ:
                    $read = $contentService->getChapterRead($chapter->id);
                    $this->view->setVar('read', $read);
                    break;
            }
        }

        $this->view->setVar('chapter', $chapter);
        $this->view->setVar('course', $course);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.chapter.update")
     */
    public function updateAction($id)
    {
        $chapterService = new ChapterService();

        $chapter = $chapterService->updateChapter($id);

        if ($chapter->parent_id > 0) {
            $location = $this->url->get([
                'for' => 'admin.chapter.lessons',
                'id' => $chapter->parent_id,
            ]);
        } else {
            $location = $this->url->get([
                'for' => 'admin.course.chapters',
                'id' => $chapter->course_id,
            ]);
        }

        $content = [
            'location' => $location,
            'msg' => '更新章节成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.chapter.delete")
     */
    public function deleteAction($id)
    {
        $chapterService = new ChapterService();

        $chapterService->deleteChapter($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除章节成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.chapter.restore")
     */
    public function restoreAction($id)
    {
        $chapterService = new ChapterService();

        $chapterService->restoreChapter($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除章节成功',
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/content", name="admin.chapter.content")
     */
    public function contentAction($id)
    {
        $contentService = new ChapterContentService();

        $contentService->updateChapterContent($id);

        $chapterService = new ChapterService();

        $chapter = $chapterService->getChapter($id);

        $location = $this->url->get([
            'for' => 'admin.chapter.lessons',
            'id' => $chapter->parent_id,
        ]);

        $content = [
            'location' => $location,
            'msg' => '更新课时内容成功',
        ];

        return $this->jsonSuccess($content);
    }

}
