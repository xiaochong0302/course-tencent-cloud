<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Text;

class ImUser extends Model
{

    /**
     * 在线状态
     */
    const STATUS_ONLINE = 'online';
    const STATUS_OFFLINE = 'offline';
    const STATUS_HIDE = 'hide';
    const STATUS_NONE = 'none';

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
     * 签名
     *
     * @var string
     */
    public $sign = '';

    /**
     * 皮肤
     *
     * @var string
     */
    public $skin = '';

    /**
     * 状态
     *
     * @var string
     */
    public $status = 'none';

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 好友数
     *
     * @var int
     */
    public $friend_count = 0;

    /**
     * 群组数
     *
     * @var int
     */
    public $group_count = 0;

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
        return 'kg_im_user';
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

    public function beforeSave()
    {
        if (empty($this->avatar)) {
            $this->avatar = kg_default_user_avatar_path();
        } elseif (Text::startsWith($this->avatar, 'http')) {
            $this->avatar = self::getAvatarPath($this->avatar);
        }
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

}