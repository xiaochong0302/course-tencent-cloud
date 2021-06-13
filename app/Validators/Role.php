<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\Role as RoleRepo;

class Role extends Validator
{

    public function checkRole($id)
    {
        $roleRepo = new RoleRepo();

        $role = $roleRepo->findById($id);

        if (!$role) {
            throw new BadRequestException('role.not_found');
        }

        return $role;
    }

    public function checkName($name)
    {
        $value = $this->filter->sanitize($name, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('role.name_too_short');
        }

        if ($length > 30) {
            throw new BadRequestException('role.name_too_long');
        }

        return $value;
    }

    public function checkSummary($summary)
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('role.summary_too_long');
        }

        return $value;
    }

    public function checkRoutes($routes)
    {
        if (empty($routes)) {
            throw new BadRequestException('role.routes_required');
        }

        return array_values($routes);
    }

}
