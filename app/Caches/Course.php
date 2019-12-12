<?php

namespace App\Library\Cache;

use App\Models\Course as CourseModel;
use App\Exceptions\NotFound as ModelNotFoundException;

class Course extends \Phalcon\Di\Injectable
{

    private $lifetime = 86400;

    public function getOrFail($id)
    {
        $result = $this->getById($id);

        if (!$result) {
            throw new ModelNotFoundException('course.not_found');
        }

        return $result;
    }

    public function get($id)
    {
        $cacheOptions = [
            'key' => $this->getKey($id),
            'lifetime' => $this->getLifetime(),
        ];

        $result = CourseModel::query()
                ->where('id = :id:', ['id' => $id])
                ->cache($cacheOptions)
                ->execute()
                ->getFirst();

        return $result;
    }

    public function delete($id)
    {
        $key = $this->getKey($id);

        $this->modelsCache->delete($key);
    }

    public function getKey($id)
    {
        return "course:{$id}";
    }

    public function getLifetime()
    {
        return $this->lifetime;
    }

}
