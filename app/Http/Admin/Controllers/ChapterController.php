<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Chapter as ChapterService;
use App\Http\Admin\Services\ChapterContent as ChapterContentService;
use App\Http\Admin\Services\Course as CourseService;
use App\Models\Course as CourseModel;
use App\Traits\Service as ServiceTrait;

/**
 * @RoutePrefix("/admin/chapter")
 */
class ChapterController extends Controller
{

    use ServiceTrait;

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
        $modelTypes = $courseService->getModelTypes();

        $this->view->pick('chapter/add_chapter');

        if ($type == 'lesson') {
            $this->view->pick('chapter/add_lesson');
        }

        $this->view->setVar('course', $course);
        $this->view->setVar('model_types', $modelTypes);
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

        if ($chapter->parent_id > 0) {
            $location = $this->url->get([
                'for' => 'admin.chapter.lessons',
                'id' => $chapter->parent_id,
            ]);
            $msg = '创建课时成功';
        } else {
            $location = $this->url->get([
                'for' => 'admin.course.chapters',
                'id' => $chapter->course_id,
            ]);
            $msg = '创建章节成功';
        }

        $content = [
            'location' => $location,
            'msg' => $msg,
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

            switch ($chapter->model) {
                case CourseModel::MODEL_VOD:
                    $vod = $contentService->getChapterVod($chapter->id);
                    $this->view->setVar('vod', $vod);
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
            $msg = '更新课时成功';
        } else {
            $location = $this->url->get([
                'for' => 'admin.course.chapters',
                'id' => $chapter->course_id,
            ]);
            $msg = '更新章节成功';
        }

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.chapter.delete")
     */
    public function deleteAction($id)
    {
        $chapterService = new ChapterService();

        $chapter = $chapterService->getChapter($id);

        $chapterService->deleteChapter($id);

        $msg = $chapter->parent_id > 0 ? '删除课时成功' : '删除章节成功';

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.chapter.restore")
     */
    public function restoreAction($id)
    {
        $chapterService = new ChapterService();

        $chapter = $chapterService->getChapter($id);

        $chapterService->restoreChapter($id);

        $msg = $chapter->parent_id > 0 ? '删除课时成功' : '删除章节成功';

        $location = $this->request->getHTTPReferer();

        $content = [
            'location' => $location,
            'msg' => $msg,
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

    /**
     * @Post("/{id:[0-9]+}/transcode", name="admin.chapter.transcode")
     */
    public function transcodeAction($id)
    {
        $contentService = new ChapterContentService();

        $contentService->transcode($id);

        $content = [
            'msg' => '提交转码成功',
        ];

        return $this->jsonSuccess($content);
    }

}
