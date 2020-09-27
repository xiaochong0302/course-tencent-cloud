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

}
