<?php

namespace App\Models;

class User extends Model
{

    /**
     * 教学角色
     */
    const EDU_ROLE_STUDENT = 1; // 学员
    const EDU_ROLE_TEACHER = 2; // 讲师

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 名称
     *
     * @var string
     */
    public $name;

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
     * 头像
     *
     * @var string
     */
    public $avatar;

    /**
     * 头衔
     *
     * @var string
     */
    public $title;

    /**
     * 介绍
     *
     * @var string
     */
    public $about;

    /**
     * 教学角色
     *
     * @var integer
     */
    public $edu_role;

    /**
     * 后台角色
     *
     * @var integer
     */
    public $admin_role;

    /**
     * VIP标识
     *
     * @var integer
     */
    public $vip;

    /**
     * VIP期限
     *
     * @var integer
     */
    public $vip_expiry;

    /**
     * 锁定标识
     *
     * @var integer
     */
    public $locked;

    /**
     * 锁定期限
     *
     * @var integer
     */
    public $locked_expiry;

    /**
     * 最后活跃
     *
     * @var integer
     */
    public $last_active;

    /**
     * 最后IP
     *
     * @var string
     */
    public $last_ip;

    /**
     * 创建时间
     *
     * @var integer
     */
    public $created_at;

    /**
     * 更新时间
     *
     * @var integer
     */
    public $updated_at;

    public function getSource()
    {
        return 'user';
    }

    public function beforeCreate()
    {
        $this->created_at = time();
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();
    }

    public static function eduRoles()
    {
        $list = [
            self::EDU_ROLE_STUDENT => '学员',
            self::EDU_ROLE_TEACHER => '讲师',
        ];

        return $list;
    }

}
