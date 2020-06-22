<?php

namespace App\Models;

class ImFriendUser extends Model
{

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 用户编号
     *
     * @var integer
     */
    public $user_id;

    /**
     * 好友编号
     *
     * @var integer
     */
    public $friend_id;

    /**
     * 分组编号
     *
     * @var integer
     */
    public $group_id;

    /**
     * 屏蔽标识
     *
     * @var integer
     */
    public $blocked;

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
        return 'kg_im_friend_user';
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
