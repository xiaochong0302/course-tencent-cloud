<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Text;

class ImGroup extends Model
{

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
     * 群主编号
     *
     * @var string
     */
    public $user_id;

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
     * 状态
     *
     * @var integer
     */
    public $deleted;

    /**
     * 成员数
     *
     * @var integer
     */
    public $user_count;

    /**
     * 创建时间
     *
     * @var integer
     */
    public $create_time;

    /**
     * 更新时间
     *
     * @var integer
     */
    public $update_time;

    public function getSource()
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

