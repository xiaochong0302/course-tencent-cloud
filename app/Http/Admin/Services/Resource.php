<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use App\Models\Resource as ResourceModel;
use App\Models\Upload as UploadModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\Upload as UploadRepo;
use App\Services\Storage as StorageService;
use App\Validators\Chapter as ChapterValidator;
use App\Validators\Resource as ResourceValidator;
use App\Validators\Upload as UploadValidator;

class Resource extends Service
{

    public function createResource()
    {
        $post = $this->request->getPost();

        $validator = new ChapterValidator();

        $chapter = $validator->checkChapter($post['chapter_id']);
        $course = $validator->checkCourse($chapter->course_id);

        $uploadRepo = new UploadRepo();

        $upload = $uploadRepo->findByMd5($post['upload']['md5']);

        /**
         * 腾讯COS存储可能不会返回文件md5值
         */
        if (!$upload || empty($post['upload']['md5'])) {

            $upload = new UploadModel();

            $upload->type = UploadModel::TYPE_RESOURCE;
            $upload->name = $post['upload']['name'];
            $upload->size = $post['upload']['size'];
            $upload->path = $post['upload']['path'];
            $upload->md5 = $post['upload']['md5'];
            $upload->mime = $post['upload']['mime'];

            $upload->create();
        }

        $resource = new ResourceModel();

        $resource->course_id = $course->id;
        $resource->chapter_id = $chapter->id;
        $resource->upload_id = $upload->id;

        $resource->create();

        $this->recountChapterResources($chapter);
        $this->recountCourseResources($course);

        return $upload;
    }

    public function updateResource($id)
    {
        $post = $this->request->getPost();

        $resource = $this->findOrFail($id);

        $validator = new UploadValidator();

        $upload = $validator->checkUpload($resource->upload_id);

        $data = [];

        if (isset($post['name'])) {
            $data['name'] = $validator->checkName($post['name']);
        }

        $upload->update($data);

        $resource->update();
    }

    public function deleteResource($id)
    {
        $resource = $this->findOrFail($id);

        $validator = new ResourceValidator();

        $course = $validator->checkCourse($resource->course_id);
        $chapter = $validator->checkChapter($resource->chapter_id);

        $resource->delete();

        $this->recountChapterResources($chapter);
        $this->recountCourseResources($course);
    }

    protected function findOrFail($id)
    {
        $validator = new ResourceValidator();

        return $validator->checkResource($id);
    }

    protected function recountChapterResources(ChapterModel $chapter)
    {
        $chapterRepo = new ChapterRepo();

        $chapter->resource_count = $chapterRepo->countResources($chapter->id);

        $chapter->update();

        $parent = $chapterRepo->findById($chapter->parent_id);

        $lessons = $chapterRepo->findLessons($parent->id);

        $resourceCount = 0;

        foreach ($lessons as $lesson) {
            $resourceCount += $chapterRepo->countResources($lesson->id);
        }

        $parent->resource_count = $resourceCount;

        $parent->update();
    }

    protected function recountCourseResources(CourseModel $course)
    {
        $courseRepo = new CourseRepo();

        $course->resource_count = $courseRepo->countResources($course->id);

        $course->update();
    }

}
