<?php

namespace App\Caches;

use App\Repos\Question as QuestionRepo;

class Question extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "question:{$id}";
    }

    public function getContent($id = null)
    {
        $questionRepo = new QuestionRepo();

        $question = $questionRepo->findById($id);

        return $question ?: null;
    }

}
