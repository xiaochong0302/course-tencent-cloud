<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;

class ChapterVod extends Validator
{

    public function checkFileId($fileId)
    {
        $value = $this->filter->sanitize($fileId, ['trim', 'string']);

        if (!CommonValidator::intNumber($value)) {
            throw new BadRequestException('chapter_vod.invalid_file_id');
        }

        return $value;
    }

    public function checkDuration($duration)
    {
        $value = $value = $this->filter->sanitize($duration, ['trim', 'int']);

        if ($value < 10 || $value > 10 * 3600) {
            throw new BadRequestException('chapter_vod.invalid_duration');
        }

        return $value;
    }

    public function checkFileUrl($url)
    {
        $value = $this->filter->sanitize($url, ['trim', 'string']);

        if (!CommonValidator::url($value)) {
            throw new BadRequestException('chapter_vod.invalid_file_url');
        }

        $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));

        /**
         * 点播只支持mp4,m3u8格式
         */
        if (!in_array($ext, ['mp4', 'm3u8'])) {
            throw new BadRequestException('chapter_vod.invalid_file_ext');
        }

        return $value;
    }

}
