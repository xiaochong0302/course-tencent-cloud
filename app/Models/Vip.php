<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Text;

class Vip extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 标题
     *
     * @var string
     */
    public $title = '';

    /**
     * 封面
     *
     * @var string
     */
    public $cover = '';

    /**
     * 期限（月）
     *
     * @var int
     */
    public $expiry = 0;

    /**
     * 价格
     *
     * @var float
     */
    public $price = 0.00;

    /**
     * 发布标识
     *
     * @var int
     */
    public $published = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public $update_time = 0;

    public function getSource(): string
    {
        return 'kg_vip';
    }

    public function initialize()
    {
        parent::initialize();

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

    public function beforeSave()
    {
        if (empty($this->cover)) {
            $this->cover = kg_default_vip_cover_path();
        } elseif (Text::startsWith($this->cover, 'http')) {
            $this->cover = self::getCoverPath($this->cover);
        }
    }

    public function afterFetch()
    {
        if (!Text::startsWith($this->cover, 'http')) {
            $this->cover = kg_cos_vip_cover_url($this->cover);
        }

        $this->price = (float)$this->price;
    }

    public static function getCoverPath($url)
    {
        if (Text::startsWith($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
    }

}
