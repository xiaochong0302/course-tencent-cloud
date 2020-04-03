<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

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
     * 删除标识
     *
     * @var int
     */
    public $deleted;

    /**
     * 过期时间
     *
     * @var int
     */
    public $expired_at;

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
        return 'kg_access_token';
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
        $this->id = $this->getRandId($this->user_id);

        $this->expired_at = strtotime('+2 hours');
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();
    }

    public function afterCreate()
    {
        $refreshToken = new RefreshToken();

        $refreshToken->user_id = $this->user_id;

        $refreshToken->create();
    }

    protected function getRandId($userId, $prefix = 'AT')
    {
        return md5("{$prefix}-{$userId}" . time() . rand(1000, 9999));
    }

}
