<?php

namespace App\Caches;

class HotTeacherList extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'hot_teacher_list';
    }

    public function getContent($id = null)
    {

    }

    protected function handleContent($users)
    {

    }

}
