<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Account extends Model
{

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 邮箱
     *
     * @var string
     */
    public string $email = '';

    /**
     * 手机
     *
     * @var string
     */
    public string $phone = '';

    /**
     * 密码
     *
     * @var string
     */
    public string $password = '';

    /**
     * 密盐
     *
     * @var string
     */
    public string $salt = '';

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public int $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public int $update_time = 0;

    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('kg_account');

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate(): void
    {
        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        $this->update_time = time();
    }

    public function afterCreate(): void
    {
        $user = new User();
        $user->id = $this->id;
        $user->name = "user-{$this->id}";
        $user->create();

        $userBalance = new UserBalance();
        $userBalance->user_id = $user->id;
        $userBalance->create();
    }

}
