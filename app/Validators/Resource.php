<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\Resource as ResourceRepo;

class Resource extends Validator
{

    public function checkResource($id)
    {
        $resourceRepo = new ResourceRepo();

        $resource = $resourceRepo->findById($id);

        if (!$resource) {
            throw new BadRequestException('resource.not_found');
        }

        return $resource;
    }

    public function checkCourse($id)
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

    public function checkUpload($id)
    {
        $validator = new Upload();

        return $validator->checkUpload($id);
    }

}
