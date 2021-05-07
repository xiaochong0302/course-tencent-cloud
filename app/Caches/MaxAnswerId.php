<?php

namespace App\Caches;

use App\Models\Answer as AnswerModel;

class MaxAnswerId extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_answer_id';
    }

    public function getContent($id = null)
    {
        $answer = AnswerModel::findFirst(['order' => 'id DESC']);

        return $answer->id ?? 0;
    }

}
