<?php

namespace App\Builders;

use App\Repos\Upload as UploadRepo;

class ResourceList extends Builder
{

    public function handleUploads($relations)
    {
        $uploads = $this->getUploads($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['upload'] = $uploads[$value['upload_id']] ?? new \stdClass();
        }

        return $relations;
    }

    public function getUploads($relations)
    {
        $ids = kg_array_column($relations, 'upload_id');

        $uploadRepo = new UploadRepo();

        $columns = ['id', 'name', 'path', 'mime', 'md5', 'size'];

        $uploads = $uploadRepo->findByIds($ids, $columns);

        $result = [];

        foreach ($uploads->toArray() as $upload) {
            $result[$upload['id']] = $upload;
        }

        return $result;
    }

}
