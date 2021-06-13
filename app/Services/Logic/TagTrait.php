<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
