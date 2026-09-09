<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Chapter as ChapterModel;

class ChapterVod extends Validator
{

    public function checkFileId(int $fileId): int
    {
        $value = $this->filter->sanitize($fileId, ['trim', 'int']);

        if (!CommonValidator::intNumber($value)) {
            throw new BadRequestException('chapter_vod.invalid_file_id');
        }

        return $value;
    }

    public function checkTransMode(string $mode): string
    {
        if (!array_key_exists($mode, ChapterModel::transModeTypes())) {
            throw new BadRequestException('chapter_vod.invalid_trans_mode');
        }

        return $mode;
    }

}
