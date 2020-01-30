<?php

namespace App\Models;

use App\Caches\MaxCourseId as MaxCourseIdCache;
use App\Services\CourseCacheSyncer;
use App\Services\CourseIndexSyncer;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Course extends Model
{

    /**
     * 模型
     */
    const MODEL_VOD = 'vod'; // 点播
    const MODEL_LIVE = 'live'; // 直播
    const MODEL_READ = 'read'; // 图文

    /**
     * 级别
     */
    const LEVEL_ENTRY = 'entry'; // 入门
    const LEVEL_JUNIOR = 'junior'; // 初级
    const LEVEL_MEDIUM = 'medium'; // 中级
    const LEVEL_SENIOR = 'senior'; // 高级

    /**
     * @var array
     *
     * 点播扩展属性
     */
    protected $_vod_attrs = ['duration' => 0];

    /**
     * @var array
     *
     * 直播扩展属性
     */
    protected $_live_attrs = ['start_date' => '', 'end_date' => ''];

    /**
     * @var array
     *
     * 图文扩展属性
     */
    protected $_read_attrs = ['duration' => 0, 'word_count' => 0];

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 类型
     *
     * @var string
     */
    public $class_id;

    /**
     * 标题
     *
     * @var string
     */
    public $title;

    /**
     * 封面
     *
     * @var string
     */
    public $cover;

    /**
     * 简介
     *
     * @var string
     */
    public $summary;

    /**
     * 关键字
     *
     * @var string
     */
    public $keywords;

    /**
     * 详情
     *
     * @var string
     */
    public $details;

    /**
     * 市场价格
     *
     * @var float
     */
    public $market_price;

    /**
     * 会员价格
     *
     * @var float
     */
    public $vip_price;

    /**
     * 有效期限（天）
     *
     * @var int
     */
    public $expiry;

    /**
     * 综合得分
     *
     * @var float
     */
    public $score;

    /**
     * 模式类型
     *
     * @var string
     */
    public $model;

    /**
     * 难度级别
     *
     * @var string
     */
    public $level;

    /**
     * 扩展属性
     *
     * @var string
     */
    public $attrs;

    /**
     * 课时数
     *
     * @var int
     */
    public $lesson_count;

    /**
     * 学员数
     *
     * @var int
     */
    public $user_count;

    /**
     * 评论数
     *
     * @var int
     */
    public $comment_count;

    /**
     * 评价数
     *
     * @var int
     */
    public $review_count;

    /**
     * 收藏数
     *
     * @var int
     */
    public $favorite_count;

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
    public $created_at;

    /**
     * 更新时间
     *
     * @var int
     */
    public $updated_at;

    public function getSource()
    {
        return 'course';
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
        $this->created_at = time();

        $attrs = [];

        switch ($this->model) {
            case Course::MODEL_VOD:
                $attrs = $this->_vod_attrs;
                break;
            case Course::MODEL_LIVE:
                $attrs = $this->_live_attrs;
                break;
            case Course::MODEL_READ:
                $attrs = $this->_read_attrs;
                break;
        }

        if (!empty($attrs)) {
            $this->attrs = kg_json_encode($attrs);
        }

    }

    public function beforeUpdate()
    {
        $this->updated_at = time();

        if (!empty($this->attrs)) {
            $this->attrs = kg_json_encode($this->attrs);
        }
    }

    public function afterFetch()
    {
        $this->market_price = (float)$this->market_price;
        $this->vip_price = (float)$this->vip_price;

        if (!empty($this->attrs)) {
            $this->attrs = json_decode($this->attrs, true);
        }
    }

    public function afterCreate()
    {
        $maxCourseIdCache = new MaxCourseIdCache();
        $maxCourseIdCache->rebuild();
    }

    public function afterUpdate()
    {
        $courseCacheSyncer = new CourseCacheSyncer();
        $courseCacheSyncer->addItem($this->id);

        $courseIndexSyncer = new CourseIndexSyncer();
        $courseIndexSyncer->addItem($this->id);
    }

    public static function models()
    {
        $list = [
            self::MODEL_VOD => '点播',
            self::MODEL_LIVE => '直播',
            self::MODEL_READ => '图文',
        ];

        return $list;
    }

    public static function levels()
    {
        $list = [
            self::LEVEL_ENTRY => '入门',
            self::LEVEL_JUNIOR => '初级',
            self::LEVEL_MEDIUM => '中级',
            self::LEVEL_SENIOR => '高级',
        ];

        return $list;
    }

}
