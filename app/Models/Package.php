<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Package extends Model
{

    /**
     * 主键编号
     *
     * @var integer
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
     * @var integer
     */
    public $course_count;

    /**
     * 发布标识
     *
     * @var integer
     */
    public $published;

    /**
     * 删除标识
     *
     * @var integer
     */
    public $deleted;

    /**
     * 创建时间
     *
     * @var integer
     */
    public $created_at;

    /**
     * 更新时间
     *
     * @var integer
     */
    public $updated_at;

    public function getSource()
    {
        return 'package';
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

}
