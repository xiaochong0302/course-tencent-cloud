<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Comment extends Model
{

    /**
     * 条目类型
     */
    const ITEM_CHAPTER = 1; // 章节
    const ITEM_ARTICLE = 2; // 文章
    const ITEM_ANSWER = 3; // 回答

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 内容
     *
     * @var string
     */
    public $content;

    /**
     * 父级编号
     *
     * @var integer
     */
    public $parent_id;

    /**
     * 作者编号
     *
     * @var integer
     */
    public $owner_id;

    /**
     * 目标用户
     *
     * @var integer
     */
    public $to_user_id;

    /**
     * 条目编号
     *
     * @var integer
     */
    public $item_id;

    /**
     * 条目类型
     *
     * @var integer
     */
    public $item_type;

    /**
     * 终端类型
     *
     * @var integer
     */
    public $client_type;

    /**
     * 终端IP
     *
     * @var integer
     */
    public $client_ip;

    /**
     * 发布标识
     *
     * @var integer
     */
    public $published;

    /**
     * 删除标识
     *
     * @var integer
     */
    public $deleted;

    /**
     * 回复数
     *
     * @var integer
     */
    public $reply_count;

    /**
     * 点赞数
     *
     * @var integer
     */
    public $like_count;

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

    public function getSource(): string
    {
        return 'kg_comment';
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

    public static function itemTypes()
    {
        return [
            self::ITEM_CHAPTER => '章节',
            self::ITEM_ARTICLE => '文章',
            self::ITEM_ANSWER => '回答',
        ];
    }

}
