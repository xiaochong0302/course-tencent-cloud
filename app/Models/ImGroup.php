<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Text;

class ImGroup extends Model
{

    /**
     * 群组类型
     */
    const TYPE_COURSE = 'course'; // 课程
    const TYPE_CHAT = 'chat'; // 聊天

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 课程编号
     *
     * @var int
     */
    public $course_id;

    /**
     * 群主编号
     *
     * @var int
     */
    public $user_id;

    /**
     * 群组类型
     *
     * @var string
     */
    public $type;

    /**
     * 名称
     *
     * @var string
     */
    public $name;

    /**
     * 图标
     *
     * @var string
     */
    public $avatar;

    /**
     * 简介
     *
     * @var string
     */
    public $about;

    /**
     * 发布状态
     *
     * @var int
     */
    public $published;

    /**
     * 删除状态
     *
     * @var int
     */
    public $deleted;

    /**
     * 成员数
     *
     * @var int
     */
    public $user_count;

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
        $this->create_time = time();

        if (Text::startsWith($this->avatar, 'http')) {
            $this->avatar = self::getAvatarPath($this->avatar);
        } elseif (empty($this->avatar)) {
            $this->avatar = kg_default_avatar_path();
        }
    }

    public function beforeUpdate()
    {
        $this->update_time = time();

        if ($this->deleted == 1) {
            $this->published = 0;
        }
    }

    public function afterFetch()
    {
        if (!Text::startsWith($this->avatar, 'http')) {
            $this->avatar = kg_ci_avatar_img_url($this->avatar);
        }
    }

    public static function getAvatarPath($url)
    {
        if (Text::startsWith($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
    }

}

