<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\Topic as TopicRepo;

class Topic extends Validator
{

    public function checkTopic($id)
    {
        $topicRepo = new TopicRepo();

        $topic = $topicRepo->findById($id);

        if (!$topic) {
            throw new BadRequestException('topic.not_found');
        }

        return $topic;
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('topic.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('topic.title_too_long');
        }

        return $value;
    }

    public function checkSummary($summary)
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('topic.summary_too_long');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('topic.invalid_publish_status');
        }

        return $status;
    }

}
