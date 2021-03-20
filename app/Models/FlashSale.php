<?php

namespace App\Models;

use App\Caches\MaxFlashSaleId as MaxFlashSaleIdCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class FlashSale extends Model
{

    /**
     * 条目类型
     */
    const ITEM_COURSE = 1; // 课程
    const ITEM_PACKAGE = 2; // 套餐
    const ITEM_VIP = 3; // 会员

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 物品编号
     *
     * @var string
     */
    public $item_id = 0;

    /**
     * 物品类型
     *
     * @var int
     */
    public $item_type = 0;

    /**
     * 物品信息
     *
     * @var array|string
     */
    public $item_info = [];

    /**
     * 开始时间
     *
     * @var int
     */
    public $start_time = 0;

    /**
     * 结束时间
     *
     * @var int
     */
    public $end_time = 0;

    /**
     * 时间场次
     *
     * @var array|string
     */
    public $schedules = [];

    /**
     * 价格
     *
     * @var float
     */
    public $price = 0.00;

    /**
     * 库存
     *
     * @var int
     */
    public $stock = 0;

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
        return 'kg_flash_sale';
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
        if (is_array($this->item_info)) {
            $this->item_info = kg_json_encode($this->item_info);
        }

        if (is_array($this->schedules)) {
            $this->schedules = kg_json_encode($this->schedules);
        }

        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        if (is_array($this->item_info)) {
            $this->item_info = kg_json_encode($this->item_info);
        }

        if (is_array($this->schedules)) {
            $this->schedules = kg_json_encode($this->schedules);
        }

        if ($this->deleted == 1) {
            $this->published = 0;
        }

        $this->update_time = time();
    }

    public function afterCreate()
    {
        $cache = new MaxFlashSaleIdCache();

        $cache->rebuild();
    }

    public function afterFetch()
    {
        if (is_string($this->item_info)) {
            $this->item_info = json_decode($this->item_info, true);
        }

        if (is_string($this->schedules)) {
            $this->schedules = json_decode($this->schedules, true);
        }
    }

    public static function itemTypes()
    {
        return [
            self::ITEM_COURSE => '课程',
            self::ITEM_PACKAGE => '套餐',
            self::ITEM_VIP => '会员',
        ];
    }

    public static function schedules()
    {
        $result = [];

        foreach (range(8, 20, 2) as $hour) {
            $result[] = [
                'name' => sprintf('%02d点', $hour),
                'hour' => sprintf('%02d', $hour),
                'start_time' => sprintf('%02d:%02d:%02d', $hour, 0, 0),
                'end_time' => sprintf('%02d:%02d:%02d', $hour + 1, 59, 59)
            ];
        }

        return $result;
    }

}