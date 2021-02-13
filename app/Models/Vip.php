<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

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
     * 期限（天）
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

    public function afterFetch()
    {
        $this->price = (float)$this->price;
    }

}