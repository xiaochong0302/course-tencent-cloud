<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Chapter as ChapterService;
use App\Http\Admin\Services\ChapterContent as ChapterContentService;
use App\Http\Admin\Services\Config as ConfigService;
use App\Http\Admin\Services\Course as CourseService;
use App\Models\Course as CourseModel;

/**
 * @RoutePrefix("/admin/chapter")
 */
class ChapterController extends Controller
{

    /**
     * @Get("/{id}/lessons", name="admin.chapter.lessons")
     */
    public function lessonsAction($id)
    {
        $chapterService = new ChapterService();
        $courseService = new CourseService();

        $chapter = $chapterService->getChapter($id);
        $course = $courseService->getCourse($chapter->course_id);
        $lessons = $chapterService->getLessons($chapter->id);

        $this->view->setVar('lessons', $lessons);
        $this->view->setVar('chapter', $chapter);
        $this->view->setVar('course', $course);
    }

    /**
     * @Get("/add", name="admin.chapter.add")
     */
    public function addAction()
    {
        $courseId = $this->request->getQuery('course_id');
        $parentId = $this->request->getQuery('parent_id');
        $type = $this->request->getQuery('type');

        $chapterService = new ChapterService();

        $course = $chapterService->getCourse($courseId);
        $courseChapters = $chapterService->getCourseChapters($courseId);

        $this->view->setVar('course', $course);
        $this->view->setVar('parent_id', $parentId);
        $this->view->setVar('course_chapters', $courseChapters);

        if ($type == 'chapter') {
            $this->view->pick('chapter/add_chapter');
        } else {
            $this->view->pick('chapter/add_lesson');
        }
    }

    /**
     * @Post("/create", name="admin.chapter.create")
     */
    public function createAction()
    {
        $service = new ChapterService();

        $chapter = $service->createChapter();

        $location = $this->url->get([
            'for' => 'admin.course.chapters',
            'id' => $chapter->course_id,
        ]);

        $content = [
            'location' => $location,
            'msg' => '创建章节成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Get("/{id}/edit", name="admin.chapter.edit")
     */
    public function editAction($id)
    {
        $contentService = new ChapterContentService();
        $chapterService = new ChapterService();
        $configService = new ConfigService();

        $chapter = $chapterService->getChapter($id);
        $course = $chapterService->getCourse($chapter->course_id);
        $storage = $configService->getSectionConfig('storage');

        switch ($course->model) {
            case CourseModel::MODEL_VOD:
                $vod = $contentService->getChapterVod($chapter->id);
                $translatedFiles = $contentService->getTranslatedFiles($vod->file_id);
                $this->view->setVar('vod', $vod);
                $this->view->setVar('translated_files', $translatedFiles);
                break;
            case CourseModel::MODEL_LIVE:
                $live = $contentService->getChapterLive($chapter->id);
                $this->view->setVar('live', $live);
                break;
            case CourseModel::MODEL_ARTICLE:
                $article = $contentService->getChapterArticle($chapter->id);
                $this->view->setVar('article', $article);
                break;
        }

        $this->view->setVar('storage', $storage);
        $this->view->setVar('chapter', $chapter);
        $this->view->setVar('course', $course);

        if ($chapter->parent_id > 0) {
            $this->view->pick('chapter/edit_lesson');
        } else {
            $this->view->pick('chapter/edit_chapter');
        }
    }

    /**
     * @Post("/{id}/update", name="admin.chapter.update")
     */
    public function updateAction($id)
    {
        $service = new ChapterService();

        $chapter = $service->updateChapter($id);

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

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id}/delete", name="admin.chapter.delete")
     */
    public function deleteAction($id)
    {
        $service = new ChapterService();

        $service->deleteChapter($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除章节成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id}/restore", name="admin.chapter.restore")
     */
    public function restoreAction($id)
    {
        $service = new ChapterService();

        $service->restoreChapter($id);

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => '删除章节成功',
        ];

        return $this->ajaxSuccess($content);
    }

    /**
     * @Post("/{id}/content", name="admin.chapter.content")
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

        return $this->ajaxSuccess($content);
    }

}
