<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class Notification extends Model
{

    /* -------------- 课程相关 -------------- */

    const TYPE_COURSE_CREATED = 100;
    const TYPE_COURSE_UPDATED = 101;
    const TYPE_COURSE_DELETED = 102;
    const TYPE_COURSE_RESTORED = 103;
    const TYPE_COURSE_APPROVED = 104;
    const TYPE_COURSE_REJECTED = 105;
    const TYPE_COURSE_FEATURED = 106;
    const TYPE_COURSE_CONSULTED = 107;
    const TYPE_COURSE_FAVORITED = 108;
    const TYPE_COURSE_REVIEWED = 109;

    /* -------------- 章节相关 -------------- */

    const TYPE_CHAPTER_CREATED = 120;
    const TYPE_CHAPTER_UPDATED = 121;
    const TYPE_CHAPTER_DELETED = 122;
    const TYPE_CHAPTER_RESTORED = 123;
    const TYPE_CHAPTER_APPROVED = 124;
    const TYPE_CHAPTER_REJECTED = 125;
    const TYPE_CHAPTER_CONSULTED = 126;
    const TYPE_CHAPTER_COMMENTED = 127;
    const TYPE_CHAPTER_LIKED = 128;

    /* -------------- 咨询相关 -------------- */

    const TYPE_CONSULT_CREATED = 140;
    const TYPE_CONSULT_UPDATED = 141;
    const TYPE_CONSULT_DELETED = 142;
    const TYPE_CONSULT_RESTORED = 143;
    const TYPE_CONSULT_APPROVED = 144;
    const TYPE_CONSULT_REJECTED = 145;
    const TYPE_CONSULT_COMMENTED = 146;
    const TYPE_CONSULT_LIKED = 147;

    /* -------------- 评价相关 -------------- */

    const TYPE_REVIEW_CREATED = 160;
    const TYPE_REVIEW_UPDATED = 161;
    const TYPE_REVIEW_DELETED = 162;
    const TYPE_REVIEW_RESTORED = 163;
    const TYPE_REVIEW_APPROVED = 164;
    const TYPE_REVIEW_REJECTED = 165;
    const TYPE_REVIEW_COMMENTED = 166;
    const TYPE_REVIEW_LIKED = 167;

    /* -------------- 文章相关 -------------- */

    const TYPE_ARTICLE_CREATED = 180;
    const TYPE_ARTICLE_UPDATED = 181;
    const TYPE_ARTICLE_DELETED = 182;
    const TYPE_ARTICLE_RESTORED = 183;
    const TYPE_ARTICLE_APPROVED = 184;
    const TYPE_ARTICLE_REJECTED = 185;
    const TYPE_ARTICLE_FEATURED = 186;
    const TYPE_ARTICLE_COMMENTED = 187;
    const TYPE_ARTICLE_FAVORITED = 188;
    const TYPE_ARTICLE_LIKED = 189;

    /* -------------- 问题相关 -------------- */

    const TYPE_QUESTION_CREATED = 200;
    const TYPE_QUESTION_UPDATED = 201;
    const TYPE_QUESTION_DELETED = 202;
    const TYPE_QUESTION_RESTORED = 203;
    const TYPE_QUESTION_APPROVED = 204;
    const TYPE_QUESTION_REJECTED = 205;
    const TYPE_QUESTION_ANSWERED = 206;
    const TYPE_QUESTION_COMMENTED = 207;
    const TYPE_QUESTION_FAVORITED = 208;
    const TYPE_QUESTION_LIKED = 209;

    /* -------------- 回答相关 -------------- */

    const TYPE_ANSWER_CREATED = 220;
    const TYPE_ANSWER_UPDATED = 221;
    const TYPE_ANSWER_DELETED = 222;
    const TYPE_ANSWER_RESTORED = 223;
    const TYPE_ANSWER_APPROVED = 224;
    const TYPE_ANSWER_REJECTED = 225;
    const TYPE_ANSWER_ACCEPTED = 226;
    const TYPE_ANSWER_COMMENTED = 227;
    const TYPE_ANSWER_LIKED = 228;

    /* -------------- 评论相关 -------------- */

    const TYPE_COMMENT_CREATED = 500;
    const TYPE_COMMENT_UPDATED = 501;
    const TYPE_COMMENT_DELETED = 502;
    const TYPE_COMMENT_RESTORED = 503;
    const TYPE_COMMENT_APPROVED = 504;
    const TYPE_COMMENT_REJECTED = 505;
    const TYPE_COMMENT_REPLIED = 506;
    const TYPE_COMMENT_LIKED = 507;

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 发送方编号
     *
     * @var int
     */
    public $sender_id = 0;

    /**
     * 接收方编号
     *
     * @var int
     */
    public $receiver_id = 0;

    /**
     * 事件编号
     *
     * @var int
     */
    public $event_id = 0;

    /**
     * 事件类型
     *
     * @var int
     */
    public $event_type = 0;

    /**
     * 事件内容
     *
     * @var array|string
     */
    public $event_info = [];

    /**
     * 阅读标识
     *
     * @var int
     */
    public $viewed = 0;

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
        return 'kg_notification';
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
        if (is_array($this->event_info)) {
            $this->event_info = kg_json_encode($this->event_info);
        }
    }

    public function afterFetch()
    {
        if (is_string($this->event_info)) {
            $this->event_info = json_decode($this->event_info, true);
        }
    }

    public function afterCreate()
    {
        /**
         * @var $user User
         */
        $user = User::findFirst($this->receiver_id);

        $user->notice_count += 1;

        $user->update();
    }

}
