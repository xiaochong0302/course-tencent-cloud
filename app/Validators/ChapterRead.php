<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;

class ChapterRead extends Validator
{

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim']);

        $length = kg_strlen($value);

        if ($length < 10) {
            throw new BadRequestException('chapter_read.content_too_short');
        }

        if ($length > 30000) {
            throw new BadRequestException('chapter_read.content_too_long');
        }

        return kg_clean_html($value);
    }

}
