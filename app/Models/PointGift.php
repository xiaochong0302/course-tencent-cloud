<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Text;

class PointGift extends Model
{

    /**
     * 礼物类型
     */
    const TYPE_COURSE = 1; // 课程
    const TYPE_GOODS = 2; // 商品
    const TYPE_VIP = 3; // 会员

    /**
     * 课程扩展属性
     *
     * @var array
     */
    protected $_course_attrs = [
        'id' => 0,
        'title' => '',
        'price' => 0,
    ];

    /**
     * 会员扩展属性
     *
     * @var array
     */
    protected $_vip_attrs = [
        'id' => 0,
        'title' => '',
        'price' => 0,
    ];

    /**
     * 商品扩展属性
     *
     * @var array
     */
    protected $_goods_attrs = [
        'source' => '',
        'price' => 0,
        'url' => '',
    ];

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 名称
     *
     * @var string
     */
    public $name = '';

    /**
     * 封面
     *
     * @var string
     */
    public $cover = '';

    /**
     * 详情
     *
     * @var string
     */
    public $details = '';

    /**
     * 属性
     *
     * @var array|string
     */
    public $attrs = [];

    /**
     * 类型
     *
     * @var int
     */
    public $type = 0;

    /**
     * 库存
     *
     * @var int
     */
    public $stock = 0;

    /**
     * 所需积分
     *
     * @var int
     */
    public $point = 0;

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
     * 兑换限额
     *
     * @var int
     */
    public $redeem_limit = 1;

    /**
     * 兑换数量
     *
     * @var int
     */
    public $redeem_count = 0;

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
        return 'kg_point_gift';
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
        if (empty($this->attrs)) {
            if ($this->type == self::TYPE_COURSE) {
                $this->attrs = $this->_course_attrs;
            } elseif ($this->type == self::TYPE_VIP) {
                $this->attrs = $this->_vip_attrs;
            } elseif ($this->type == self::TYPE_GOODS) {
                $this->attrs = $this->_goods_attrs;
            }
        }

        if (is_array($this->attrs)) {
            $this->attrs = kg_json_encode($this->attrs);
        }

        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        if (is_array($this->attrs)) {
            $this->attrs = kg_json_encode($this->attrs);
        }

        $this->update_time = time();
    }

    public function beforeSave()
    {
        if (empty($this->cover)) {
            $this->cover = kg_default_gift_cover_path();
        } elseif (Text::startsWith($this->cover, 'http')) {
            $this->cover = self::getCoverPath($this->cover);
        }
    }

    public function afterFetch()
    {
        if (!Text::startsWith($this->cover, 'http')) {
            $this->cover = kg_cos_gift_cover_url($this->cover);
        }

        if (is_string($this->attrs)) {
            $this->attrs = json_decode($this->attrs, true);
        }
    }

    public static function getCoverPath($url)
    {
        if (Text::startsWith($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
    }

    public static function types()
    {
        return [
            self::TYPE_COURSE => '课程',
            self::TYPE_GOODS => '商品',
            self::TYPE_VIP => '会员',
        ];
    }

}
