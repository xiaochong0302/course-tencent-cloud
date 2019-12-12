<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Exceptions\NotFound as NotFoundException;
use App\Repos\Role as RoleRepo;

class Role extends Validator
{

    /**
     * @param integer $id
     * @return \App\Models\Role
     * @throws NotFoundException
     */
    public function checkRole($id)
    {
        $roleRepo = new RoleRepo();

        $role = $roleRepo->findById($id);

        if (!$role) {
            throw new NotFoundException('role.not_found');
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
