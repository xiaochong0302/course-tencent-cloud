<?php

namespace App\Models;

use App\Caches\MaxCourseId as MaxCourseIdCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Text;

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
     * 主分类编号
     *
     * @var int
     */
    public $category_id;

    /**
     * 主教师编号
     *
     * @var int
     */
    public $teacher_id;

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
     * 学习期限（月）
     *
     * @var int
     */
    public $study_expiry;

    /**
     * 退款期限（天）
     *
     * @var int
     */
    public $refund_expiry;

    /**
     * 用户评价
     *
     * @var float
     */
    public $rating;

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
     * @var string|array
     */
    public $attrs;

    /**
     * 学员数
     *
     * @var int
     */
    public $user_count;

    /**
     * 课时数
     *
     * @var int
     */
    public $lesson_count;

    /**
     * 套餐数
     *
     * @var int
     */
    public $package_count;

    /**
     * 评论数
     *
     * @var int
     */
    public $comment_count;

    /**
     * 咨询数
     *
     * @var int
     */
    public $consult_count;

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
    public $create_time;

    /**
     * 更新时间
     *
     * @var int
     */
    public $update_time;

    public function getSource(): string
    {
        return 'kg_course';
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

        if (Text::startsWith($this->cover, 'http')) {
            $this->cover = self::getCoverPath($this->cover);
        } elseif (empty($this->cover)) {
            $this->cover = kg_default_cover_path();
        }

        if (!empty($attrs)) {
            $this->attrs = kg_json_encode($attrs);
        }
    }

    public function beforeUpdate()
    {
        $this->update_time = time();

        if (Text::startsWith($this->cover, 'http')) {
            $this->cover = self::getCoverPath($this->cover);
        }

        if (is_array($this->attrs)) {
            $this->attrs = kg_json_encode($this->attrs);
        }
    }

    public function afterCreate()
    {
        $cache = new MaxCourseIdCache();

        $cache->rebuild();
    }

    public function afterFetch()
    {
        $this->market_price = (float)$this->market_price;
        $this->vip_price = (float)$this->vip_price;
        $this->rating = (float)$this->rating;
        $this->score = (float)$this->score;

        if (!Text::startsWith($this->cover, 'http')) {
            $this->cover = kg_ci_cover_img_url($this->cover);
        }

        if (!empty($this->attrs) && is_string($this->attrs)) {
            $this->attrs = json_decode($this->attrs, true);
        }
    }

    public static function getCoverPath($url)
    {
        if (Text::startsWith($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
    }

    public static function modelTypes()
    {
        return [
            self::MODEL_VOD => '点播',
            self::MODEL_LIVE => '直播',
            self::MODEL_READ => '图文',
        ];
    }

    public static function levelTypes()
    {
        return [
            self::LEVEL_ENTRY => '入门',
            self::LEVEL_JUNIOR => '初级',
            self::LEVEL_MEDIUM => '中级',
            self::LEVEL_SENIOR => '高级',
        ];
    }

    public static function sortTypes()
    {
        return [
            'score' => '综合',
            'rating' => '好评',
            'latest' => '最新',
            'popular' => '最热',
            'free' => '免费',
        ];
    }

    public static function studyExpiryOptions()
    {
        return [
            1 => '1个月',
            3 => '3个月',
            6 => '6个月',
            12 => '12个月',
            36 => '36个月',
        ];
    }

    public static function refundExpiryOptions()
    {
        return [
            7 => '7天',
            14 => '14天',
            30 => '30天',
            90 => '90天',
            180 => '180天',
        ];
    }

}
