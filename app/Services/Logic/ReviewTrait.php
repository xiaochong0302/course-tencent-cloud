<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Models\Review as ReviewModel;
use App\Validators\Review as ReviewValidator;

trait ReviewTrait
{

    protected function checkReview(int $id): ReviewModel
    {
        $validator = new ReviewValidator();

        return $validator->checkReview($id);
    }

}
