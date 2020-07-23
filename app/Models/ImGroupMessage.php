<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class ImGroupMessage extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 群组编号
     *
     * @var int
     */
    public $group_id;

    /**
     * 发送方编号
     *
     * @var int
     */
    public $sender_id;

    /**
     * 内容
     *
     * @var string
     */
    public $content;

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
    public $create_time;

    /**
     * 更新时间
     *
     * @var int
     */
    public $update_time;

    public function getSource(): string
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
