<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Models\Page as PageModel;
use App\Validators\Page as PageValidator;

trait PageTrait
{

    protected function checkPage(string|int $id): PageModel
    {
        $validator = new PageValidator();

        return $validator->checkPage($id);
    }

}
