<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\PointGift as PointGiftModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class PointHotGiftList extends Cache
{

    /**
     * 过期时间
     *
     * @var int
     */
    protected $lifetime = 86400;

    /**
     * 显示个数
     *
     * @var int
     */
    protected $limit = 5;

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'point_hot_gift_list';
    }

    public function getContent($id = null)
    {
        $gifts = $this->findGifts($this->limit);

        if (count($gifts) == 0) {
            return [];
        }

        $result = [];

        foreach ($gifts as $gift) {
            $result[] = [
                'id' => $gift->id,
                'name' => $gift->name,
                'cover' => $gift->cover,
                'details' => $gift->details,
                'type' => $gift->type,
                'point' => $gift->point,
                'redeem_count' => $gift->redeem_count,
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|PointGiftModel[]
     */
    protected function findGifts($limit = 5)
    {
        return PointGiftModel::query()
            ->where('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('redeem_count DESC')
            ->limit($limit)
            ->execute();
    }

}
