<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use App\Services\Sync\QuestionIndex as QuestionIndexSync;
use App\Services\Sync\QuestionScore as QuestionScoreSync;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Text;

class Question extends Model
{

    /**
     * 发布状态
     */
    const PUBLISH_PENDING = 1; // 审核中
    const PUBLISH_APPROVED = 2; // 已发布
    const PUBLISH_REJECTED = 3; // 未通过

    /**
     * 自增编号
     *
     * @var integer
     */
    public $id = 0;

    /**
     * 分类编号
     *
     * @var integer
     */
    public $category_id = 0;

    /**
     * 提问者编号
     *
     * @var integer
     */
    public $owner_id = 0;

    /**
     * 最后回应用户
     *
     * @var integer
     */
    public $last_replier_id = 0;

    /**
     * 最后回答编号
     *
     * @var integer
     */
    public $last_answer_id = 0;

    /**
     * 采纳答案编号
     *
     * @var integer
     */
    public $accept_answer_id = 0;

    /**
     * 标题
     *
     * @var string
     */
    public $title = '';

    /**
     * 封面
     *
     * @var string
     */
    public $cover = '';

    /**
     * 标签
     *
     * @var array|string
     */
    public $tags = [];

    /**
     * 关键字
     *
     * @var string
     */
    public $keywords = '';

    /**
     * 概要
     *
     * @var string
     */
    public $summary = '';

    /**
     * 内容
     *
     * @var string
     */
    public $content = '';

    /**
     * 综合得分
     *
     * @var float
     */
    public $score = 0.00;

    /**
     * 悬赏积分
     *
     * @var integer
     */
    public $bounty = 0;

    /**
     * 匿名标识
     *
     * @var integer
     */
    public $anonymous = 0;

    /**
     * 解决标识
     *
     * @var integer
     */
    public $solved = 0;

    /**
     * 关闭标识
     *
     * @var integer
     */
    public $closed = 0;

    /**
     * 状态标识
     *
     * @var integer
     */
    public $published = self::PUBLISH_PENDING;

    /**
     * 推荐标识
     *
     * @var integer
     */
    public $featured = 0;

    /**
     * 删除标识
     *
     * @var integer
     */
    public $deleted = 0;

    /**
     * 终端类型
     *
     * @var integer
     */
    public $client_type = 0;

    /**
     * 终端IP
     *
     * @var string
     */
    public $client_ip = '';

    /**
     * 浏览数
     *
     * @var integer
     */
    public $view_count = 0;

    /**
     * 答案数
     *
     * @var integer
     */
    public $answer_count = 0;

    /**
     * 评论数
     *
     * @var integer
     */
    public $comment_count = 0;

    /**
     * 收藏数
     *
     * @var integer
     */
    public $favorite_count = 0;

    /**
     * 点赞数
     *
     * @var integer
     */
    public $like_count = 0;

    /**
     * 举报数
     *
     * @var integer
     */
    public $report_count = 0;

    /**
     * 回应时间
     *
     * @var integer
     */
    public $last_reply_time = 0;

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
        return 'kg_question';
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
        if (time() - $this->update_time > 3 * 3600) {
            $sync = new QuestionIndexSync();
            $sync->addItem($this->id);

            $sync = new QuestionScoreSync();
            $sync->addItem($this->id);
        }

        $this->update_time = time();
    }

    public function beforeSave()
    {
        if (Text::startsWith($this->cover, 'http')) {
            $this->cover = self::getCoverPath($this->cover);
        }

        if (is_array($this->tags)) {
            $this->tags = kg_json_encode($this->tags);
        }
    }

    public function afterFetch()
    {
        /**
         * 问题封面非必要，有则处理，无则略过
         */
        if (!empty($this->cover) && !Text::startsWith($this->cover, 'http')) {
            $this->cover = kg_cos_article_cover_url($this->cover);
        }

        if (is_string($this->tags)) {
            $this->tags = json_decode($this->tags, true);
        }
    }

    public static function getCoverPath($url)
    {
        if (Text::startsWith($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
    }

    public static function publishTypes()
    {
        return [
            self::PUBLISH_PENDING => '审核中',
            self::PUBLISH_APPROVED => '已发布',
            self::PUBLISH_REJECTED => '未通过',
        ];
    }

    public static function sortTypes()
    {
        return [
            'latest' => '最新问题',
            'active' => '最新回答',
            'unanswered' => '尚未回答',
            'featured' => '推荐问题',
        ];
    }

}
