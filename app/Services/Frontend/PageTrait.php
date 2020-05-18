<?php

namespace App\Services\Frontend;

use App\Validators\Page as PageValidator;

trait PageTrait
{

    public function checkPage($id)
    {
        $validator = new PageValidator();

        return $validator->checkPage($id);
    }

    public function checkPageCache($id)
    {
        $validator = new PageValidator();

        return $validator->checkPageCache($id);
    }

}
