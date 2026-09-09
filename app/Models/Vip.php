<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Vip extends Model
{

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 标题
     *
     * @var string
     */
    public string $title = '';

    /**
     * 封面
     *
     * @var string
     */
    public string $cover = '';

    /**
     * 期限（月）
     *
     * @var int
     */
    public int $expiry = 0;

    /**
     * 价格
     *
     * @var float
     */
    public float $price = 0.00;

    /**
     * 发布标识
     *
     * @var int
     */
    public int $published = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public int $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public int $update_time = 0;

    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('kg_vip');

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate(): void
    {
        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        $this->update_time = time();
    }

    public function beforeSave(): void
    {
        if (empty($this->cover)) {
            $this->cover = kg_default_vip_cover_path();
        } elseif (str_starts_with($this->cover, 'http')) {
            $this->cover = self::getCoverPath($this->cover);
        }
    }

    public function afterFetch(): void
    {
        if (!str_starts_with($this->cover, 'http')) {
            $this->cover = kg_cos_vip_cover_url($this->cover);
        }

        $this->price = (float)$this->price;
    }

    public static function getCoverPath(string $url): string
    {
        if (str_starts_with($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
    }

}
