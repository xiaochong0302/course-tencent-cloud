<?php

namespace App\Validators;

use App\Caches\MaxUploadId as MaxUploadIdCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\Upload as UploadRepo;

class Upload extends Validator
{

    public function checkUpload($id)
    {
        $this->checkId($id);

        $uploadRepo = new UploadRepo();

        $upload = $uploadRepo->findById($id);

        if (!$upload) {
            throw new BadRequestException('upload.not_found');
        }

        return $upload;
    }

    public function checkId($id)
    {
        $id = intval($id);

        $maxIdCache = new MaxUploadIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException('upload.not_found');
        }
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
