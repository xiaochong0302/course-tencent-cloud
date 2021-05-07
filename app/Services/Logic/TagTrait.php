<?php

namespace App\Services\Logic;

use App\Validators\Tag as TagValidator;

trait TagTrait
{

    public function checkTag($id)
    {
        $validator = new TagValidator();

        return $validator->checkTag($id);
    }

    public function checkTagCache($id)
    {
        $validator = new TagValidator();

        return $validator->checkTagCache($id);
    }

}
