<?php

namespace App\Models;

use App\Caches\MaxCommentId as MaxCommentIdCache;
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
    public $id = 0;

    /**
     * 内容
     *
     * @var string
     */
    public $content = '';

    /**
     * 父级编号
     *
     * @var integer
     */
    public $parent_id = 0;

    /**
     * 作者编号
     *
     * @var integer
     */
    public $owner_id = 0;

    /**
     * 目标用户
     *
     * @var integer
     */
    public $to_user_id = 0;

    /**
     * 条目编号
     *
     * @var integer
     */
    public $item_id = 0;

    /**
     * 条目类型
     *
     * @var integer
     */
    public $item_type = 0;

    /**
     * 终端类型
     *
     * @var integer
     */
    public $client_type = 0;

    /**
     * 终端IP
     *
     * @var integer
     */
    public $client_ip = '';

    /**
     * 发布标识
     *
     * @var integer
     */
    public $published = 1;

    /**
     * 删除标识
     *
     * @var integer
     */
    public $deleted = 0;

    /**
     * 回复数
     *
     * @var integer
     */
    public $reply_count = 0;

    /**
     * 点赞数
     *
     * @var integer
     */
    public $like_count = 0;

    /**
     * 创建时间
     *
     * @var integer
     */
    public $create_time = 0;

    /**
     * 更新时间
     *
     * @var integer
     */
    public $update_time = 0;

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

    public function afterCreate()
    {
        $cache = new MaxCommentIdCache();

        $cache->rebuild();
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
