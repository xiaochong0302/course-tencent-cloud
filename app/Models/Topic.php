<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Topic extends Model
{

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 名称
     *
     * @var string
     */
    public $name;

    /**
     * 简介
     *
     * @var string
     */
    public $summary;

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
        return 'topic';
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
