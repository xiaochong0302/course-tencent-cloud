<?php

namespace App\Models;

class AccessToken extends Model
{

    /**
     * 主键编号
     *
     * @var string
     */
    public $id;

    /**
     * 用户编号
     *
     * @var int
     */
    public $user_id;

    /**
     * 回收标识
     *
     * @var int
     */
    public $revoked;

    /**
     * 过期时间
     *
     * @var int
     */
    public $expiry_time;

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

    public function getSource()
    {
        return 'kg_access_token';
    }

    public function beforeCreate()
    {
        $this->id = $this->getRandId($this->user_id);

        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

    protected function getRandId($userId, $prefix = 'AT')
    {
        return md5("{$prefix}-{$userId}" . time() . rand(1000, 9999));
    }

}
