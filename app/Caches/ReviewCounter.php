<?php

namespace App\Caches;

use App\Repos\Review as ReviewRepo;

class ReviewCounter extends Counter
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "review_counter:{$id}";
    }

    public function getContent($id = null)
    {
        $reviewRepo = new ReviewRepo();

        $review = $reviewRepo->findById($id);

        if (!$review) return null;

        return [
            'agree_count' => $review->agree_count,
            'oppose_count' => $review->oppose_count,
        ];
    }

}
