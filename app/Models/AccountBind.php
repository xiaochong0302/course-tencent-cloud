<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class AccountBind extends Model
{

    /**
     * 服务商类型
     */
    const PROVIDER_QQ = 'qq';
    const PROVIDER_WEIXIN = 'weixin';
    const PROVIDER_WEIBO = 'weibo';

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 服务商
     *
     * @var string
     */
    public $provider;

    /**
     * 外部用户编号
     *
     * @var string
     */
    public $open_id;

    /**
     * 内部用户编号
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
        return 'kg_account_bind';
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
