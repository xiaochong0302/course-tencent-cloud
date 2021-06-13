<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Repos\Topic as TopicRepo;

class Topic extends Cache
{

    protected $lifetime = 7 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "topic:{$id}";
    }

    public function getContent($id = null)
    {
        $topicRepo = new TopicRepo();

        $topic = $topicRepo->findById($id);

        return $topic ?: null;
    }

}
