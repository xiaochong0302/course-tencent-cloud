<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validator\Common as CommonValidator;

class ChapterAudio extends Validator
{

    public function checkFileId($fileId)
    {
        $value = $this->filter->sanitize($fileId, ['trim', 'string']);

        if (!CommonValidator::intNumber($value)) {
            throw new BadRequestException('chapter_audio.invalid_file_id');
        }

        return $value;
    }

}
