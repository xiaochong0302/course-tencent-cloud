<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Comment as CommentModel;

class MaxCommentId extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_comment_id';
    }

    public function getContent($id = null)
    {
        $comment = CommentModel::findFirst(['order' => 'id DESC']);

        return $comment->id ?? 0;
    }

}
