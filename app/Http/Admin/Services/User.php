<?php

namespace App\Http\Admin\Services;

use App\Builders\UserList as UserListBuilder;
use App\Library\Paginator\Query as PaginateQuery;
use App\Models\Account as AccountModel;
use App\Repos\Account as AccountRepo;
use App\Repos\Role as RoleRepo;
use App\Repos\User as UserRepo;
use App\Validators\Account as AccountValidator;
use App\Validators\User as UserValidator;

class User extends Service
{

    public function getRoles()
    {
        $roleRepo = new RoleRepo();

        return $roleRepo->findAll(['deleted' => 0]);
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

        return $this->handleUsers($pager);
    }

    public function getUser($id)
    {
        return $this->findOrFail($id);
    }

    public function getAccount($id)
    {
        $accountRepo = new AccountRepo();

        return $accountRepo->findById($id);
    }

    public function createUser()
    {
        $post = $this->request->getPost();

        $accountValidator = new AccountValidator();

        $phone = $accountValidator->checkPhone($post['phone']);
        $password = $accountValidator->checkPassword($post['password']);

        $accountValidator->checkIfPhoneTaken($post['phone']);

        $userValidator = new UserValidator();

        $eduRole = $userValidator->checkEduRole($post['edu_role']);
        $adminRole = $userValidator->checkAdminRole($post['admin_role']);

        $account = new AccountModel();

        $account->phone = $phone;
        $account->password = $password;

        $account->create();

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

        $user->edu_role = $eduRole;
        $user->admin_role = $adminRole;

        $user->update();

        if ($adminRole > 0) {
            $this->updateAdminUserCount($adminRole);
        }

        return $user;
    }

    public function updateUser($id)
    {
        $user = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new UserValidator();

        $data = [];

        if (isset($post['name'])) {
            $data['name'] = $validator->checkName($post['name']);
            if ($post['name'] != $user->name) {
                $validator->checkIfNameTaken($post['name']);
            }
        }

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

        if (isset($post['vip'])) {
            $data['vip'] = $validator->checkVipStatus($post['vip']);
        }

        if (!empty($post['vip_expiry_time'])) {
            $data['vip_expiry_time'] = $validator->checkVipExpiryTime($post['vip_expiry_time']);
            if ($data['vip_expiry_time'] < time()) {
                $data['vip'] = 0;
            }
        }

        if (isset($post['locked'])) {
            $data['locked'] = $validator->checkLockStatus($post['locked']);
        }

        if (!empty($post['lock_expiry_time'])) {
            $data['lock_expiry_time'] = $validator->checkLockExpiryTime($post['lock_expiry_time']);
            if ($data['lock_expiry_time'] < time()) {
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

    public function updateAccount($id)
    {
        $post = $this->request->getPost();

        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($id);

        $validator = new AccountValidator();

        $data = [];

        if (!empty($post['phone'])) {
            $data['phone'] = $validator->checkPhone($post['phone']);
            if ($post['phone'] != $account->phone) {
                $validator->checkIfPhoneTaken($post['phone']);
            }
        }

        if (!empty($post['email'])) {
            $data['email'] = $validator->checkEmail($post['email']);
            if ($post['email'] != $account->email) {
                $validator->checkIfEmailTaken($post['email']);
            }
        }

        if (!empty($post['password'])) {
            $data['password'] = $validator->checkPassword($post['password']);
        }

        $account->update($data);

        return $account;
    }

    protected function findOrFail($id)
    {
        $validator = new UserValidator();

        return $validator->checkUser($id);
    }

    protected function updateAdminUserCount($roleId)
    {
        $roleRepo = new RoleRepo();

        $role = $roleRepo->findById($roleId);

        if (!$role) return;

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
