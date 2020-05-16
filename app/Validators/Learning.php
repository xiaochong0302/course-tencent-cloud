<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\Chapter as ChapterRepo;

class Learning extends Validator
{

    public function checkChapterId($chapterId)
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($chapterId);

        if (!$chapter) {
            throw new BadRequestException('learning.invalid_chapter_id');
        }

        return $chapterId;
    }

    public function checkRequestId($requestId)
    {
        if (!$requestId) {
            throw new BadRequestException('learning.invalid_request_id');
        }

        return $requestId;
    }

    public function checkInterval($interval)
    {
        $value = $this->filter->sanitize($interval, ['trim', 'int']);

        /**
         * 兼容秒和毫秒
         */
        if ($value > 1000) {
            $value = intval($value / 1000);
        }

        if ($value < 5) {
            throw new BadRequestException('learning.invalid_interval');
        }

        return $value;
    }

    public function checkPosition($position)
    {
        $value = $this->filter->sanitize($position, ['trim', 'int']);

        if ($value < 0 || $value > 3 * 3600) {
            throw new BadRequestException('learning.invalid_position');
        }

        return $value;
    }

}
