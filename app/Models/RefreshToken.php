<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

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
        return 'kg_refresh_token';
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

        $this->expired_at = strtotime('+30 days');

        $this->created_at = time();
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();
    }

    protected function getRandId($userId, $prefix = 'RT')
    {
        return md5("{$prefix}-{$userId}" . time() . rand(1000, 9999));
    }
}
