<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use App\Caches\MaxUserId as MaxUserIdCache;
use App\Caches\User as UserCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Text;

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
    public $id = 0;

    /**
     * 名称
     *
     * @var string
     */
    public $name = '';

    /**
     * 头像
     *
     * @var string
     */
    public $avatar = '';

    /**
     * 头衔
     *
     * @var string
     */
    public $title = '';

    /**
     * 介绍
     *
     * @var string
     */
    public $about = '';

    /**
     * 地区
     *
     * @var string
     */
    public $area = '';

    /**
     * 性别
     *
     * @var int
     */
    public $gender = self::GENDER_NONE;

    /**
     * 会员标识
     *
     * @var int
     */
    public $vip = 0;

    /**
     * 锁定标识
     *
     * @var int
     */
    public $locked = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 教学角色
     *
     * @var int
     */
    public $edu_role = self::EDU_ROLE_STUDENT;

    /**
     * 后台角色
     *
     * @var int
     */
    public $admin_role = 0;

    /**
     * 课程数
     *
     * @var int
     */
    public $course_count = 0;

    /**
     * 文章数
     *
     * @var int
     */
    public $article_count = 0;

    /**
     * 提问数
     *
     * @var int
     */
    public $question_count = 0;

    /**
     * 回答数
     *
     * @var int
     */
    public $answer_count = 0;

    /**
     * 评论数
     *
     * @var int
     */
    public $comment_count = 0;

    /**
     * 收藏数
     *
     * @var int
     */
    public $favorite_count = 0;

    /**
     * 通知数
     *
     * @var int
     */
    public $notice_count = 0;

    /**
     * 会员期限
     *
     * @var int
     */
    public $vip_expiry_time = 0;

    /**
     * 锁定期限
     *
     * @var int
     */
    public $lock_expiry_time = 0;

    /**
     * 活跃时间
     *
     * @var int
     */
    public $active_time = 0;

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
        return 'kg_user';
    }

    public function initialize()
    {
        parent::initialize();

        $this->keepSnapshots(true);

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

    public function beforeSave()
    {
        if (empty($this->avatar)) {
            $this->avatar = kg_default_user_avatar_path();
        } elseif (Text::startsWith($this->avatar, 'http')) {
            $this->avatar = self::getAvatarPath($this->avatar);
        }
    }

    public function afterCreate()
    {
        $cache = new MaxUserIdCache();

        $cache->rebuild();
    }

    public function afterUpdate()
    {
        $userCache = new UserCache();

        $userCache->rebuild($this->id);
    }

    public function afterFetch()
    {
        if (!Text::startsWith($this->avatar, 'http')) {
            $this->avatar = kg_cos_user_avatar_url($this->avatar);
        }
    }

    public static function getAvatarPath($url)
    {
        if (Text::startsWith($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
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
