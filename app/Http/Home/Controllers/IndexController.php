<?php

namespace App\Http\Home\Controllers;

class IndexController extends Controller
{

    /**
     * @Get("/", name="home.index")
     */
    public function indexAction()
    {
        echo phpinfo(); exit;
    }

    /**
     * @Get("/phpinfo", name="home.phpinfo")
     */
    public function phpinfoAction()
    {
        echo phpinfo(); exit;
    }

}
