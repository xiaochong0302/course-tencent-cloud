<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Text;

class App extends Model
{

    /**
     * 应用类型
     */
    const TYPE_PC = 'pc';
    const TYPE_H5 = 'h5';
    const TYPE_IOS = 'ios';
    const TYPE_ANDROID = 'android';
    const TYPE_MP_WEIXIN = 'mp_weixin';
    const TYPE_MP_ALIPAY = 'mp_alipay';

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
     * key
     *
     * @var string
     */
    public $key;

    /**
     * secret
     *
     * @var string
     */
    public $secret;

    /**
     * 类型
     *
     * @var string
     */
    public $type;

    /**
     * 备注
     *
     * @var string
     */
    public $remark;

    /**
     * 发布标识
     *
     * @var int
     */
    public $published;

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

    public function getSource(): string
    {
        return 'kg_app';
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
        $this->key = Text::random(Text::RANDOM_ALNUM, 16);
        $this->secret = Text::random(Text::RANDOM_ALNUM, 16);
        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        if ($this->deleted == 1) {
            $this->published = 0;
        }

        $this->update_time = time();
    }

    public static function types()
    {
        return [
            self::TYPE_PC => 'PC客户端',
            self::TYPE_H5 => 'H5客户端',
            self::TYPE_IOS => 'IOS客户端',
            self::TYPE_ANDROID => 'Android客户端',
            self::TYPE_MP_WEIXIN => '微信小程序',
            self::TYPE_MP_ALIPAY => '支付宝小程序',
        ];
    }

}
