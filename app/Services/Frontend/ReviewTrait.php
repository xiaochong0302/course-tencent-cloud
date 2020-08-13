<?php

namespace App\Services\Frontend;

use App\Validators\Review as ReviewValidator;

trait ReviewTrait
{

    public function checkReview($id)
    {
        $validator = new ReviewValidator();

        return $validator->checkReview($id);
    }

}
