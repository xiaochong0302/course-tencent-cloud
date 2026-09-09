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

    /**
     * @var int
     */
    protected int $lifetime = 365 * 86400;

    public function getKey($id = null): string
    {
        return 'max-chapter-id';
    }

    public function getContent($id = null): int
    {
        $chapter = ChapterModel::findFirst(['order' => 'id DESC']);

        return $chapter->id ?? 0;
    }

}
