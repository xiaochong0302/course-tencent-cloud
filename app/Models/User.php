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

class User extends Model
{

    /**
     * 性别类型
     */
    const int GENDER_MALE = 1; // 男
    const int GENDER_FEMALE = 2; // 女
    const int GENDER_NONE = 3; // 保密

    /**
     * 教学角色
     */
    const int EDU_ROLE_STUDENT = 1; // 学员
    const int EDU_ROLE_TEACHER = 2; // 讲师

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 名称
     *
     * @var string
     */
    public string $name = '';

    /**
     * 头像
     *
     * @var string
     */
    public string $avatar = '';

    /**
     * 头衔
     *
     * @var string
     */
    public string $title = '';

    /**
     * 介绍
     *
     * @var string
     */
    public string $about = '';

    /**
     * 资料
     *
     * @var string
     */
    public string $profile = '';

    /**
     * 地区
     *
     * @var string
     */
    public string $area = '';

    /**
     * 性别
     *
     * @var int
     */
    public int $gender = self::GENDER_NONE;

    /**
     * 会员标识
     *
     * @var int
     */
    public int $vip = 0;

    /**
     * 锁定标识
     *
     * @var int
     */
    public int $locked = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

    /**
     * 教学角色
     *
     * @var int
     */
    public int $edu_role = self::EDU_ROLE_STUDENT;

    /**
     * 后台角色
     *
     * @var int
     */
    public int $admin_role = 0;

    /**
     * 在学课程数
     *
     * @var int
     */
    public int $study_course_count = 0;

    /**
     * 在学试卷数
     *
     * @var int
     */
    public int $study_paper_count = 0;

    /**
     * 在学文章数
     *
     * @var int
     */
    public int $study_article_count = 0;

    /**
     * 提问数
     *
     * @var int
     */
    public int $question_count = 0;

    /**
     * 回答数
     *
     * @var int
     */
    public int $answer_count = 0;

    /**
     * 评论数
     *
     * @var int
     */
    public int $comment_count = 0;

    /**
     * 收藏数
     *
     * @var int
     */
    public int $favorite_count = 0;

    /**
     * 通知数
     *
     * @var int
     */
    public int $notice_count = 0;

    /**
     * 会员期限
     *
     * @var int
     */
    public int $vip_expiry_time = 0;

    /**
     * 锁定期限
     *
     * @var int
     */
    public int $lock_expiry_time = 0;

    /**
     * 活跃时间
     *
     * @var int
     */
    public int $active_time = 0;

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

        $this->setSource('kg_user');

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

    public function beforeSave(): void
    {
        if (empty($this->avatar)) {
            $this->avatar = kg_default_user_avatar_path();
        } elseif (str_starts_with($this->avatar, 'http')) {
            $this->avatar = self::getAvatarPath($this->avatar);
        }
    }

    public function afterCreate(): void
    {
        $cache = new MaxUserIdCache();

        $cache->rebuild();
    }

    public function afterFetch(): void
    {
        if (!str_starts_with($this->avatar, 'http')) {
            $this->avatar = kg_cos_user_avatar_url($this->avatar);
        }
    }

    public static function getAvatarPath(string $url): string
    {
        if (str_starts_with($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
    }

    public static function genderTypes(): array
    {
        return [
            self::GENDER_MALE => '男',
            self::GENDER_FEMALE => '女',
            self::GENDER_NONE => '保密',
        ];
    }

    public static function eduRoleTypes(): array
    {
        return [
            self::EDU_ROLE_STUDENT => '学员',
            self::EDU_ROLE_TEACHER => '讲师',
        ];
    }

}
