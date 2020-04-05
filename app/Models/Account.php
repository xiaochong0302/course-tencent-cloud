<?php

namespace App\Models;

use App\Library\Util\Password;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Account extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 邮箱
     *
     * @var string
     */
    public $email;

    /**
     * 手机
     *
     * @var string
     */
    public $phone;

    /**
     * 密码
     *
     * @var string
     */
    public $password;

    /**
     * 密盐
     *
     * @var string
     */
    public $salt;

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
        return 'kg_account';
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
        $this->salt = Password::salt();

        $this->password = Password::hash($this->password, $this->salt);

        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        if (!empty($this->password)) {

            $this->salt = Password::salt();

            $this->password = Password::hash($this->password, $this->salt);
        }

        $this->update_time = time();
    }

    public function afterCreate()
    {
        $user = new User();

        $user->id = $this->id;
        $user->name = "user_{$this->id}";

        $user->create();
    }

}
