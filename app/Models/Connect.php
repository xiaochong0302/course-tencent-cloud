<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Connect extends Model
{

    const PROVIDER_QQ = 1; // QQ
    const PROVIDER_WEIXIN = 2; // 微信扫码
    const PROVIDER_WEIBO = 3; // 新浪微博
    const PROVIDER_WECHAT_OA = 4; // 微信公众号
    const PROVIDER_WECHAT_MINI = 5; // 微信小程序


    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 用户编号
     *
     * @var int
     */
    public $user_id = 0;

    /**
     * 联合ID
     *
     * @var string
     */
    public $union_id = '';

    /**
     * 开放ID
     *
     * @var string
     */
    public $open_id = '';

    /**
     * 开放名称
     *
     * @var string
     */
    public $open_name = '';

    /**
     * 开放头像
     *
     * @var string
     */
    public $open_avatar = '';

    /**
     * 提供商
     *
     * @var int
     */
    public $provider = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public $update_time = 0;

    public function getSource(): string
    {
        return 'kg_connect';
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
