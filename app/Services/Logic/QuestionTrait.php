<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Validators\Question as QuestionValidator;

trait QuestionTrait
{

    public function checkQuestion($id)
    {
        $validator = new QuestionValidator();

        return $validator->checkQuestion($id);
    }

}
