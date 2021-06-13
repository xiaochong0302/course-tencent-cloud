<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Models\Resource as ResourceModel;
use App\Models\Upload as UploadModel;
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

        if (!$upload) {

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

        $chapter->resource_count += 1;
        $chapter->update();

        $course->resource_count += 1;
        $course->update();

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

        $validator = new UploadValidator();

        $upload = $validator->checkUpload($resource->upload_id);

        $storageService = new StorageService();

        $storageService->deleteObject($upload->path);

        $resource->delete();

        if ($course->resource_count > 1) {
            $course->resource_count -= 1;
            $course->update();
        }

        if ($chapter->resource_count > 1) {
            $chapter->resource_count -= 1;
            $chapter->update();
        }
    }

    protected function findOrFail($id)
    {
        $validator = new ResourceValidator();

        return $validator->checkResource($id);
    }

}
