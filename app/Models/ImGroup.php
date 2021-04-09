<?php

namespace App\Models;

use App\Caches\MaxImGroupId as MaxImGroupIdCache;
use App\Services\Sync\GroupIndex as GroupIndexSync;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Text;

class ImGroup extends Model
{

    /**
     * 群组类型
     */
    const TYPE_COURSE = 1; // 课程
    const TYPE_CHAT = 2; // 聊天
    const TYPE_STAFF = 3; // 员工

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 群主编号
     *
     * @var int
     */
    public $owner_id = 0;

    /**
     * 课程编号
     *
     * @var int
     */
    public $course_id = 0;

    /**
     * 群组类型
     *
     * @var int
     */
    public $type = 0;

    /**
     * 名称
     *
     * @var string
     */
    public $name = '';

    /**
     * 图标
     *
     * @var string
     */
    public $avatar = '';

    /**
     * 简介
     *
     * @var string
     */
    public $about = '';

    /**
     * 发布状态
     *
     * @var int
     */
    public $published = 1;

    /**
     * 删除状态
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 成员数
     *
     * @var int
     */
    public $user_count = 0;

    /**
     * 消息数
     *
     * @var int
     */
    public $msg_count = 0;

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
        return 'kg_im_group';
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
        if (empty($this->avatar)) {
            $this->avatar = kg_default_group_avatar_path();
        } elseif (Text::startsWith($this->avatar, 'http')) {
            $this->avatar = self::getAvatarPath($this->avatar);
        }

        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        if (time() - $this->update_time > 3 * 3600) {
            $sync = new GroupIndexSync();
            $sync->addItem($this->id);
        }

        if (Text::startsWith($this->avatar, 'http')) {
            $this->avatar = self::getAvatarPath($this->avatar);
        }

        if ($this->deleted == 1) {
            $this->published = 0;
        }

        $this->update_time = time();
    }

    public function afterCreate()
    {
        $cache = new MaxImGroupIdCache();

        $cache->rebuild();
    }

    public function afterFetch()
    {
        if (!Text::startsWith($this->avatar, 'http')) {
            $this->avatar = kg_cos_group_avatar_url($this->avatar);
        }
    }

    public static function getAvatarPath($url)
    {
        if (Text::startsWith($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
    }

    public static function types()
    {
        return [
            self::TYPE_COURSE => '课程',
            self::TYPE_CHAT => '聊天',
            self::TYPE_STAFF => '员工',
        ];
    }

}