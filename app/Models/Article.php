<?php

namespace App\Models;

use App\Caches\MaxArticleId as MaxArticleIdCache;
use App\Services\Sync\ArticleIndex as ArticleIndexSync;
use App\Services\Sync\ArticleScore as ArticleScoreSync;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Article extends Model
{

    /**
     * 来源类型
     */
    const SOURCE_ORIGIN = 1; // 原创
    const SOURCE_REPRINT = 2; // 转载
    const SOURCE_TRANSLATE = 3; // 翻译

    /**
     * 发布状态
     */
    const PUBLISH_PENDING = 1; // 审核中
    const PUBLISH_APPROVED = 2; // 已发布
    const PUBLISH_REJECTED = 3; // 未通过

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

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
     * 简介
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
     * 标签
     *
     * @var array|string
     */
    public $tags = [];

    /**
     * 作者编号
     *
     * @var int
     */
    public $owner_id = 0;

    /**
     * 分类编号
     *
     * @var int
     */
    public $category_id = 0;

    /**
     * 来源类型
     *
     * @var int
     */
    public $source_type = 0;

    /**
     * 来源网址
     *
     * @var string
     */
    public $source_url = '';

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
     * 综合得分
     *
     * @var float
     */
    public $score = 0.00;

    /**
     * 私有标识
     *
     * @var int
     */
    public $private = 0;

    /**
     * 推荐标识
     *
     * @var int
     */
    public $featured = 0;

    /**
     * 发布标识
     *
     * @var int
     */
    public $published = self::PUBLISH_PENDING;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 关闭标识
     *
     * @var int
     */
    public $closed = 0;

    /**
     * 文字数
     *
     * @var int
     */
    public $word_count = 0;

    /**
     * 浏览数
     *
     * @var int
     */
    public $view_count = 0;

    /**
     * 评论数
     *
     * @var int
     */
    public $comment_count = 0;

    /**
     * 收藏数
     *
     * @var int
     */
    public $favorite_count = 0;

    /**
     * 点赞数
     *
     * @var int
     */
    public $like_count = 0;

    /**
     * 举报数
     *
     * @var integer
     */
    public $report_count;

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
        return 'kg_article';
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
            $sync = new ArticleIndexSync();
            $sync->addItem($this->id);

            $sync = new ArticleScoreSync();
            $sync->addItem($this->id);
        }

        $this->update_time = time();
    }

    public function beforeSave()
    {
        if (is_array($this->tags) || is_object($this->tags)) {
            $this->tags = kg_json_encode($this->tags);
        }
    }

    public function afterCreate()
    {
        $cache = new MaxArticleIdCache();

        $cache->rebuild();
    }

    public function afterFetch()
    {
        if (is_string($this->tags)) {
            $this->tags = json_decode($this->tags, true);
        }
    }

    public static function sourceTypes()
    {
        return [
            self::SOURCE_ORIGIN => '原创',
            self::SOURCE_REPRINT => '转载',
            self::SOURCE_TRANSLATE => '翻译',
        ];
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
            'latest' => '最新',
            'popular' => '最热',
            'featured' => '推荐',
        ];
    }

}