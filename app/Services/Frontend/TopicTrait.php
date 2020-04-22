<?php

namespace App\Services\Frontend;

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
