<?php

namespace App\Models;

use App\Caches\MaxPackageId as MaxPackageIdCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Package extends Model
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
     * 简介
     *
     * @var string
     */
    public $summary = '';

    /**
     * 优惠价格
     *
     * @var float
     */
    public $market_price = 0.00;

    /**
     * 会员价格
     *
     * @var float
     */
    public $vip_price = 0.00;

    /**
     * 课程数量
     *
     * @var int
     */
    public $course_count = 0;

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
        return 'kg_package';
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
        if ($this->deleted == 1) {
            $this->published = 0;
        }

        $this->update_time = time();
    }

    public function afterCreate()
    {
        $cache = new MaxPackageIdCache();

        $cache->rebuild();
    }

    public function afterFetch()
    {
        $this->market_price = (float)$this->market_price;
        $this->vip_price = (float)$this->vip_price;
    }

}