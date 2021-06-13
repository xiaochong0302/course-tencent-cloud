<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Validators\Topic as TopicValidator;

trait TopicTrait
{

    public function checkTopic($id)
    {
        $validator = new TopicValidator();

        return $validator->checkTopic($id);
    }

    public function checkTopicCache($id)
    {
        $validator = new TopicValidator();

        return $validator->checkTopicCache($id);
    }

}
