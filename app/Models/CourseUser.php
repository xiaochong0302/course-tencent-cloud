<?php

namespace App\Models;

class CourseUser extends Model
{

    /**
     * 角色类型
     */
    const ROLE_STUDENT = 'student'; // 学员
    const ROLE_TEACHER = 'teacher'; // 讲师

    /**
     * 来源类型
     */
    const SOURCE_FREE = 'free'; // 免费课程
    const SOURCE_PAID = 'paid'; // 付费课程
    const SOURCE_VIP = 'vip'; // 会员免费
    const SOURCE_IMPORT = 'import'; // 后台导入

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 课程编号
     *
     * @var integer
     */
    public $course_id;

    /**
     * 用户编号
     *
     * @var integer
     */
    public $user_id;

    /**
     * 角色类型
     *
     * @var string
     */
    public $role_type;

    /**
     * 来源类型
     *
     * @var string
     */
    public $source_type;

    /**
     * 过期时间
     *
     * @var integer
     */
    public $expire_time;

    /**
     * 学习时长
     *
     * @var integer
     */
    public $duration;

    /**
     * 学习进度
     *
     * @var integer
     */
    public $progress;

    /**
     * 评价标识
     *
     * @var integer
     */
    public $reviewed;

    /**
     * 锁定标识
     *
     * @var integer
     */
    public $locked;

    /**
     * 删除标识
     *
     * @var integer
     */
    public $deleted;

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
        return 'course_user';
    }

    public function beforeCreate()
    {
        $this->created_at = time();
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();
    }

    public static function roles()
    {
        $list = [
            self::ROLE_STUDENT => '学员',
            self::ROLE_TEACHER => '讲师',
        ];

        return $list;
    }

    public static function sources()
    {
        $list = [
            self::SOURCE_FREE => '免费课程',
            self::SOURCE_PAID => '付费课程',
            self::SOURCE_VIP => '会员免费',
            self::SOURCE_IMPORT => '后台导入',
        ];

        return $list;
    }

}
