<?php

namespace App\Models;

class Report extends Model
{

    /**
     * 条目类型
     */
    const ITEM_USER = 100; // 用户
    const ITEM_GROUP = 101; // 小组
    const ITEM_COURSE = 102; // 课程
    const ITEM_CHAPTER = 103; // 章节
    const ITEM_CONSULT = 104; // 咨询
    const ITEM_REVIEW = 105; // 评价
    const ITEM_ARTICLE = 106; // 文章
    const ITEM_QUESTION = 107; // 问题
    const ITEM_ANSWER = 108; // 答案
    const ITEM_COMMENT = 109; // 评论

    /**
     * 自增编号
     *
     * @var integer
     */
    public $id;

    /**
     * 用户编号
     *
     * @var integer
     */
    public $owner_id;

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
     * 举报理由
     *
     * @var string
     */
    public $reason;

    /**
     * 处理状态
     *
     * @var integer
     */
    public $reviewed;

    /**
     * 采纳标识
     *
     * @var integer
     */
    public $accepted;

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
        return 'kg_report';
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

}