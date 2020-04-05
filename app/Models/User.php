<?php

namespace App\Models;

use App\Caches\MaxUserId as MaxUserIdCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class User extends Model
{

    /**
     * 性别类型
     */
    const GENDER_MALE = 1; // 男
    const GENDER_FEMALE = 2; // 女
    const GENDER_NONE = 3; // 保密

    /**
     * 教学角色
     */
    const EDU_ROLE_STUDENT = 1; // 学员
    const EDU_ROLE_TEACHER = 2; // 讲师

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 名称
     *
     * @var string
     */
    public $name;

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
     * 所在地
     *
     * @var string
     */
    public $location;

    /**
     * 性别
     *
     * @var int
     */
    public $gender;

    /**
     * 教学角色
     *
     * @var int
     */
    public $edu_role;

    /**
     * 后台角色
     *
     * @var int
     */
    public $admin_role;

    /**
     * VIP标识
     *
     * @var int
     */
    public $vip;

    /**
     * 锁定标识
     *
     * @var int
     */
    public $locked;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted;

    /**
     * VIP期限
     *
     * @var int
     */
    public $vip_expiry_time;

    /**
     * 锁定期限
     *
     * @var int
     */
    public $lock_expiry_time;

    /**
     * 通知数量
     *
     * @var int
     */
    public $notice_count;

    /**
     * 私信数量
     *
     * @var int
     */
    public $msg_count;

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
        return 'kg_user';
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

    public function afterCreate()
    {
        $maxUserIdCache = new MaxUserIdCache();

        $maxUserIdCache->rebuild();
    }

    public static function genderTypes()
    {
        return [
            self::GENDER_MALE => '男',
            self::GENDER_FEMALE => '女',
            self::GENDER_NONE => '保密',
        ];
    }

    public static function eduRoleTypes()
    {
        return [
            self::EDU_ROLE_STUDENT => '学员',
            self::EDU_ROLE_TEACHER => '讲师',
        ];
    }

}
