<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Chapter as ChapterModel;

class MaxChapterId extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_chapter_id';
    }

    public function getContent($id = null)
    {
        $chapter = ChapterModel::findFirst(['order' => 'id DESC']);

        return $chapter->id ?? 0;
    }

}
