<?php

namespace App\Library;

class AppInfo
{

    protected $name = '酷瓜云课堂';

    protected $alias = 'CTC';

    protected $link = 'https://koogua.com';

    protected $version = '1.2.4';

    public function __get($name)
    {
        return $this->{$name} ?? null;
    }

}