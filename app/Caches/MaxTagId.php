<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Tag as TagModel;

class MaxTagId extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_tag_id';
    }

    public function getContent($id = null)
    {
        $tag = TagModel::findFirst(['order' => 'id DESC']);

        return $tag->id ?? 0;
    }

}
