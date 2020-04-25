<?php

namespace App\Http\Web\Controllers;

class IndexController extends Controller
{

    /**
     * @Get("/", name="web.index")
     */
    public function indexAction()
    {
        $this->siteSeo->setKeywords($this->siteSettings['keywords']);
        $this->siteSeo->setDescription($this->siteSettings['description']);
    }

}
