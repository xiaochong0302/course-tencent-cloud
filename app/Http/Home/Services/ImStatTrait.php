<?php

namespace App\Http\Home\Services;

use App\Caches\ImActiveGroupList;
use App\Caches\ImActiveUserList;
use App\Caches\ImNewGroupList;
use App\Caches\ImNewUserList;

trait ImStatTrait
{

    public function getActiveGroups()
    {
        $cache = new ImActiveGroupList();

        return $cache->get();
    }

    public function getActiveUsers()
    {
        $cache = new ImActiveUserList();

        return $cache->get();
    }

    public function getNewGroups()
    {
        $cache = new ImNewGroupList();

        return $cache->get();
    }

    public function getNewUsers()
    {
        $cache = new ImNewUserList();

        return $cache->get();
    }

}
