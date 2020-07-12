<?php

namespace App\Caches;

use App\Repos\Consult as ConsultRepo;

class ConsultCounter extends Counter
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "consult_counter:{$id}";
    }

    public function getContent($id = null)
    {
        $consultRepo = new ConsultRepo();

        $consult = $consultRepo->findById($id);

        if (!$consult) return null;

        return [
            'like_count' => $consult->like_count,
        ];
    }

}
