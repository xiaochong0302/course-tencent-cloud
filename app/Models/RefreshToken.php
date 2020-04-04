<?php

namespace App\Models;

class RefreshToken extends Model
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
    public $expired_at;

    public function getSource()
    {
        return 'kg_refresh_token';
    }

    public function beforeCreate()
    {
        $this->id = $this->getRandId($this->user_id);

        $this->expired_at = strtotime('+30 days');
    }

    protected function getRandId($userId, $prefix = 'RT')
    {
        return md5("{$prefix}-{$userId}" . time() . rand(1000, 9999));
    }
}
