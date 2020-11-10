<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\App as AppModel;
use App\Repos\App as AppRepo;

class App extends Validator
{

    public function checkApp($id)
    {
        $appRepo = new AppRepo();

        $app = $appRepo->findById($id);

        if (!$app) {
            throw new BadRequestException('app.not_found');
        }

        return $app;
    }

    public function checkName($name)
    {
        $value = $this->filter->sanitize($name, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('app.name_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('app.name_too_long');
        }

        return $value;
    }

    public function checkType($type)
    {
        if (!array_key_exists($type, AppModel::types())) {
            throw new BadRequestException('app.invalid_type');
        }

        return $type;
    }

    public function checkRemark($remark)
    {
        $value = $this->filter->sanitize($remark, ['trim', 'striptags']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('app.remark_too_long');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('app.invalid_publish_status');
        }

        return $status;
    }

}
