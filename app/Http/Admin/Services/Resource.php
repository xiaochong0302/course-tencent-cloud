<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Models\Course as CourseModel;
use App\Models\Resource as ResourceModel;
use App\Models\Upload as UploadModel;
use App\Repos\Course as CourseRepo;
use App\Validators\Resource as ResourceValidator;
use App\Validators\Upload as UploadValidator;

class Resource extends Service
{

    public function createResource()
    {
        $post = $this->request->getPost();

        $validator = new ResourceValidator();

        $course = $validator->checkCourse($post['course_id']);

        $upload = new UploadModel();

        $upload->type = UploadModel::TYPE_RESOURCE;
        $upload->name = $post['upload']['name'];
        $upload->size = $post['upload']['size'];
        $upload->path = $post['upload']['path'];
        $upload->md5 = $post['upload']['md5'];
        $upload->mime = $post['upload']['mime'];

        $upload->create();

        $resource = new ResourceModel();

        $resource->course_id = $course->id;
        $resource->upload_id = $upload->id;

        $resource->create();

        $this->recountCourseResources($course);

        return $resource;
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

        return $resource;
    }

    public function deleteResource($id)
    {
        $resource = $this->findOrFail($id);

        $validator = new ResourceValidator();

        $course = $validator->checkCourse($resource->course_id);

        $resource->delete();

        $this->recountCourseResources($course);

        return $resource;
    }

    protected function findOrFail($id)
    {
        $validator = new ResourceValidator();

        return $validator->checkResource($id);
    }

    protected function recountCourseResources(CourseModel $course)
    {
        $courseRepo = new CourseRepo();

        $resourceCount = $courseRepo->countResources($course->id);

        $course->resource_count = $resourceCount;

        $course->update();
    }

}
