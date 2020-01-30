<?php

namespace App\Http\Admin\Services;

class Index extends Service
{

    public function getTopMenus()
    {
        $authMenu = new AuthMenu();

        $topMenus = $authMenu->getTopMenus();

        return kg_array_object($topMenus);
    }

    public function getLeftMenus()
    {
        $authMenu = new AuthMenu();

        $leftMenus = $authMenu->getLeftMenus();

        return kg_array_object($leftMenus);
    }

}
