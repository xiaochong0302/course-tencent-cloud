<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;

class ChapterRead extends Validator
{

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim', 'striptags']);

        $length = kg_strlen($value);

        if ($length < 10) {
            throw new BadRequestException('chapter_read.content_too_short');
        }

        if ($length > 60000) {
            throw new BadRequestException('chapter_read.content_too_long');
        }

        return $value;
    }

}
