<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Package extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 标题
     *
     * @var string
     */
    public $title;

    /**
     * 简介
     *
     * @var string
     */
    public $summary;

    /**
     * 市场价格
     *
     * @var float
     */
    public $market_price;

    /**
     * 会员价格
     *
     * @var float
     */
    public $vip_price;

    /**
     * 课程数量
     *
     * @var int
     */
    public $course_count;

    /**
     * 发布标识
     *
     * @var int
     */
    public $published;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted;

    /**
     * 创建时间
     *
     * @var int
     */
    public $created_at;

    /**
     * 更新时间
     *
     * @var int
     */
    public $updated_at;

    public function getSource()
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
        $this->created_at = time();
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();
    }

    public function afterFetch()
    {
        $this->market_price = (float)$this->market_price;
        $this->vip_price = (float)$this->vip_price;
    }

}
