<?php

namespace App\Http\Web\Services;

use App\Traits\Auth as AuthTrait;
use Phalcon\Mvc\User\Component;

class Service extends Component
{

    use AuthTrait;
}
