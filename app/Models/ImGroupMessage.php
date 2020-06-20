<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class ImGroupMessage extends Model
{

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 群组编号
     *
     * @var integer
     */
    public $group_id;

    /**
     * 用户编号
     *
     * @var integer
     */
    public $user_id;

    /**
     * 内容
     *
     * @var string
     */
    public $content;

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
    public $create_time;

    /**
     * 更新时间
     *
     * @var integer
     */
    public $update_time;

    public function getSource()
    {
        return 'kg_im_group_message';
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

}
