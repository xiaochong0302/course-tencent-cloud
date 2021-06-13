<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Repos\Help as HelpRepo;

class Help extends Cache
{

    protected $lifetime = 7 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "help:{$id}";
    }

    public function getContent($id = null)
    {
        $helpRepo = new HelpRepo();

        $help = $helpRepo->findById($id);

        return $help ?: null;
    }

}
