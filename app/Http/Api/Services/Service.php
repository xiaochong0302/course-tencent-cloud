<?php

namespace App\Http\Api\Services;

use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Validators\Filter as BaseFilter;
use Phalcon\Mvc\User\Component;


class Service extends Component
{

    public function getCurrentUser()
    {
        $token = $this->getAuthToken();

        return $token ? $this->getUser($token) : $this->getGuest();
    }

    public function getLoggedUser()
    {
        $token = $this->getAuthToken();
        
        $filter = new BaseFilter();
        
        $filter->checkAuthToken($token);
        
        $user = $this->getUser($token);
        
        $filter->checkAuthUser($user);
        
        return $user;
    }

    private function getAuthToken()
    {
        $token = null;

        if ($this->cookies->has('token')) {

            $cookie = $this->cookies->get('token');

            $token = $cookie->getValue();
        }

        return $token;
    }

    private function getGuest()
    {
        $guest = new UserModel();

        $guest->id = 0;
        $guest->name = 'guest';

        return $guest;
    }

    private function getUser($token)
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById($token);

        return $user;
    }

}
