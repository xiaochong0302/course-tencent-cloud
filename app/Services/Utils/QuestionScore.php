<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Utils;

use App\Models\Question as QuestionModel;
use App\Services\Service as AppService;

class QuestionScore extends AppService
{

    public function handle(QuestionModel $question)
    {
        return $this->calculateScore($question);
    }

    protected function calculateScore(QuestionModel $question)
    {
        $weight = [
            'factor1' => 0.2,
            'factor2' => 0.1,
            'factor3' => 0.15,
            'factor4' => 0.2,
            'factor5' => 0.15,
            'factor6' => 0.2,
        ];

        $items = [
            'factor1' => 0.0,
            'factor2' => 0.0,
            'factor3' => 0.0,
            'factor4' => 0.0,
            'factor5' => 0.0,
            'factor6' => 0.0,
        ];

        if ($question->bounty > 0) {
            $items['factor1'] = 5 * $weight['factor1'];
        }

        if ($question->answer_count > 0) {
            $items['factor2'] = 5 * $weight['factor2'];
        }

        if ($question->view_count > 0) {
            $items['factor3'] = log($question->view_count) * $weight['factor3'];
        }

        if ($question->favorite_count > 0) {
            $items['factor4'] = log($question->favorite_count) * $weight['factor4'];
        }

        if ($question->like_count > 0) {
            $items['factor5'] = log($question->like_count) * $weight['factor5'];
        }

        if ($question->comment_count > 0) {
            $items['factor6'] = log($question->comment_count) * $weight['factor6'];
        }

        $score = array_sum($items) / log(time() - $question->create_time);

        return round($score, 4);
    }

}
