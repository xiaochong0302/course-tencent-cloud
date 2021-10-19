<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class UserSession extends Model
{

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
     * 会话编号
     *
     * @var string
     */
    public $session_id = '';

    /**
     * 终端类型
     *
     * @var int
     */
    public $client_type = 0;

    /**
     * 终端IP
     *
     * @var string
     */
    public $client_ip = '';

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 过期时间
     *
     * @var int
     */
    public $expire_time = 0;

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
        return 'kg_user_session';
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
