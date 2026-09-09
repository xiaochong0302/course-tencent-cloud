<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Traits\Auth as AuthTrait;
use App\Traits\Service as ServiceTrait;
use Phalcon\Di\Injectable as Component;

class Service extends Component
{
    use AuthTrait;
    use ServiceTrait;
}
