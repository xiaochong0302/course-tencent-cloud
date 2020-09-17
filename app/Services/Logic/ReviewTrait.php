<?php

namespace App\Services\Logic;

use App\Validators\Review as ReviewValidator;

trait ReviewTrait
{

    public function checkReview($id)
    {
        $validator = new ReviewValidator();

        return $validator->checkReview($id);
    }

}
