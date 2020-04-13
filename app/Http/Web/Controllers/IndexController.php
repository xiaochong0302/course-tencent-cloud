<?php

namespace App\Http\Web\Controllers;

class IndexController extends Controller
{

    /**
     * @Get("/", name="web.index")
     */
    public function indexAction()
    {
        $this->seo->setKeywords($this->site->keywords);
        $this->seo->setDescription($this->site->description);


    }

}
