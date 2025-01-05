<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\Upload as UploadRepo;

class Upload extends Validator
{

    public function checkUpload($id)
    {
        $uploadRepo = new UploadRepo();

        $upload = $uploadRepo->findById($id);

        if (!$upload) {
            throw new BadRequestException('upload.not_found');
        }

        return $upload;
    }

    public function checkName($name)
    {
        $value = $this->filter->sanitize($name, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('upload.name_too_short');
        }

        if ($length > 100) {
            throw new BadRequestException('upload.name_too_long');
        }

        return $value;
    }

}
