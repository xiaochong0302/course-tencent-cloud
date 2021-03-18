<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\Reward as RewardRepo;

class Reward extends Validator
{

    public function checkReward($id)
    {
        $rewardRepo = new RewardRepo();

        $reward = $rewardRepo->findById($id);

        if (!$reward) {
            throw new BadRequestException('reward.not_found');
        }

        return $reward;
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('reward.title_too_short');
        }

        if ($length > 30) {
            throw new BadRequestException('reward.title_too_long');
        }

        return $value;
    }

    public function checkPrice($price)
    {
        $value = $this->filter->sanitize($price, ['trim', 'float']);

        if ($value < 0.01 || $value > 10000) {
            throw new BadRequestException('reward.invalid_price');
        }

        return $value;
    }

}
