<?php

namespace App\Models;

use App\Caches\AccessToken as AccessTokenCache;

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
    public $expired_at;

    public function getSource()
    {
        return 'kg_access_token';
    }

    public function beforeCreate()
    {
        $this->id = $this->getRandId($this->user_id);

        $this->expired_at = strtotime('+2 hours');
    }

    public function afterCreate()
    {
        $accessTokenCache = new AccessTokenCache();

        $accessTokenCache->rebuild($this->id);
    }

    protected function getRandId($userId, $prefix = 'AT')
    {
        return md5("{$prefix}-{$userId}" . time() . rand(1000, 9999));
    }

}
