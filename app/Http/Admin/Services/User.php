<?php

namespace App\Http\Admin\Services;

use App\Builders\UserList as UserListBuilder;
use App\Library\Paginator\Query as PaginateQuery;
use App\Library\Util\Password as PasswordUtil;
use App\Models\User as UserModel;
use App\Repos\Role as RoleRepo;
use App\Repos\User as UserRepo;
use App\Validators\User as UserValidator;

class User extends Service
{

    public function getRoles()
    {
        $roleRepo = new RoleRepo();

        $roles = $roleRepo->findAll(['deleted' => 0]);

        return $roles;
    }

    public function getUsers()
    {
        $pageQuery = new PaginateQuery();

        $params = $pageQuery->getParams();
        $sort = $pageQuery->getSort();
        $page = $pageQuery->getPage();
        $limit = $pageQuery->getLimit();

        $userRepo = new UserRepo();

        $pager = $userRepo->paginate($params, $sort, $page, $limit);

        $result = $this->handleUsers($pager);

        return $result;
    }

    public function getUser($id)
    {
        $user = $this->findOrFail($id);

        return $user;
    }

    public function createUser()
    {
        $post = $this->request->getPost();

        $validator = new UserValidator();

        $name = $validator->checkName($post['name']);
        $password = $validator->checkPassword($post['password']);
        $eduRole = $validator->checkEduRole($post['edu_role']);
        $adminRole = $validator->checkAdminRole($post['admin_role']);

        $validator->checkIfNameTaken($name);

        $data = [];

        $data['name'] = $name;
        $data['salt'] = PasswordUtil::salt();
        $data['password'] = PasswordUtil::hash($password, $data['salt']);
        $data['edu_role'] = $eduRole;
        $data['admin_role'] = $adminRole;

        $user = new UserModel();

        $user->create($data);

        if ($user->admin_role > 0) {
            $this->updateAdminUserCount($user->admin_role);
        }

        return $user;
    }

    public function updateUser($id)
    {
        $user = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new UserValidator();

        $data = [];

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['about'])) {
            $data['about'] = $validator->checkAbout($post['about']);
        }

        if (isset($post['edu_role'])) {
            $data['edu_role'] = $validator->checkEduRole($post['edu_role']);
        }

        if (isset($post['admin_role'])) {
            $data['admin_role'] = $validator->checkAdminRole($post['admin_role']);
        }

        if (isset($post['locked'])) {
            $data['locked'] = $validator->checkLockStatus($post['locked']);
        }

        if (isset($post['lock_expiry'])) {
            $data['lock_expiry'] = $validator->checkLockExpiry($post['lock_expiry']);
            if ($data['lock_expiry'] < time()) {
                $data['locked'] = 0;
            }
        }

        $oldAdminRole = $user->admin_role;

        $user->update($data);

        if ($oldAdminRole > 0) {
            $this->updateAdminUserCount($oldAdminRole);
        }

        if ($user->admin_role > 0) {
            $this->updateAdminUserCount($user->admin_role);
        }

        return $user;
    }

    protected function findOrFail($id)
    {
        $validator = new UserValidator();

        $result = $validator->checkUser($id);

        return $result;
    }

    protected function updateAdminUserCount($roleId)
    {
        $roleRepo = new RoleRepo();

        $role = $roleRepo->findById($roleId);

        if (!$role) {
            return false;
        }

        $userCount = $roleRepo->countUsers($roleId);

        $role->user_count = $userCount;

        $role->update();
    }

    protected function handleUsers($pager)
    {
        if ($pager->total_items > 0) {

            $builder = new UserListBuilder();

            $pipeA = $pager->items->toArray();
            $pipeB = $builder->handleAdminRoles($pipeA);
            $pipeC = $builder->handleEduRoles($pipeB);
            $pipeD = $builder->arrayToObject($pipeC);

            $pager->items = $pipeD;
        }

        return $pager;
    }

}
