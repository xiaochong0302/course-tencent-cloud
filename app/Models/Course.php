<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use App\Caches\MaxCourseId as MaxCourseIdCache;
use App\Services\Sync\CourseIndex as CourseIndexSync;
use App\Services\Sync\CourseScore as CourseScoreSync;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Text;

class Course extends Model
{

    /**
     * 模型
     */
    const MODEL_VOD = 1; // 点播
    const MODEL_LIVE = 2; // 直播
    const MODEL_READ = 3; // 图文
    const MODEL_OFFLINE = 4; // 面授

    /**
     * 级别
     */
    const LEVEL_ENTRY = 1; // 入门
    const LEVEL_JUNIOR = 2; // 初级
    const LEVEL_MEDIUM = 3; // 中级
    const LEVEL_SENIOR = 4; // 高级

    /**
     * @var array
     *
     * 点播扩展属性
     */
    protected $_vod_attrs = [
        'duration' => 0,
    ];

    /**
     * @var array
     *
     * 直播扩展属性
     */
    protected $_live_attrs = [
        'start_date' => '',
        'end_date' => '',
    ];

    /**
     * @var array
     *
     * 图文扩展属性
     */
    protected $_read_attrs = [
        'duration' => 0,
        'word_count' => 0,
    ];

    /**
     * @var array
     *
     * 面授扩展属性
     */
    protected $_offline_attrs = [
        'start_date' => '',
        'end_date' => '',
        'user_limit' => 30,
        'location' => '',
    ];

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
     * 详情
     *
     * @var string
     */
    public $details = '';

    /**
     * 主分类编号
     *
     * @var int
     */
    public $category_id = 0;

    /**
     * 主教师编号
     *
     * @var int
     */
    public $teacher_id = 0;

    /**
     * 优惠价格
     *
     * @var float
     */
    public $market_price = 0.00;

    /**
     * 会员价格
     *
     * @var float
     */
    public $vip_price = 0.00;

    /**
     * 学习期限（月）
     *
     * @var int
     */
    public $study_expiry = 12;

    /**
     * 退款期限（天）
     *
     * @var int
     */
    public $refund_expiry = 7;

    /**
     * 用户评价
     *
     * @var float
     */
    public $rating = 5.00;

    /**
     * 综合得分
     *
     * @var float
     */
    public $score = 0.00;

    /**
     * 模式类型
     *
     * @var int
     */
    public $model = self::MODEL_VOD;

    /**
     * 难度级别
     *
     * @var int
     */
    public $level = self::LEVEL_JUNIOR;

    /**
     * 扩展属性
     *
     * @var array|string
     */
    public $attrs = [];

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
    public $published = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 资源数
     *
     * @var int
     */
    public $resource_count = 0;

    /**
     * 真实学员数
     *
     * @var int
     */
    public $user_count = 0;

    /**
     * 伪造学员数
     *
     * @var int
     */
    public $fake_user_count = 0;

    /**
     * 课时数
     *
     * @var int
     */
    public $lesson_count = 0;

    /**
     * 套餐数
     *
     * @var int
     */
    public $package_count = 0;

    /**
     * 咨询数
     *
     * @var int
     */
    public $consult_count = 0;

    /**
     * 评价数
     *
     * @var int
     */
    public $review_count = 0;

    /**
     * 收藏数
     *
     * @var int
     */
    public $favorite_count = 0;

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
        if (empty($this->attrs)) {
            if ($this->model == self::MODEL_VOD) {
                $this->attrs = $this->_vod_attrs;
            } elseif ($this->model == self::MODEL_LIVE) {
                $this->attrs = $this->_live_attrs;
            } elseif ($this->model == self::MODEL_READ) {
                $this->attrs = $this->_read_attrs;
            } elseif ($this->model == self::MODEL_OFFLINE) {
                $this->attrs = $this->_offline_attrs;
            }
        }

        if (is_array($this->attrs)) {
            $this->attrs = kg_json_encode($this->attrs);
        }

        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        if (time() - $this->update_time > 3 * 3600) {
            $sync = new CourseIndexSync();
            $sync->addItem($this->id);

            $sync = new CourseScoreSync();
            $sync->addItem($this->id);
        }

        if (is_array($this->attrs)) {
            $this->attrs = kg_json_encode($this->attrs);
        }

        if ($this->fake_user_count < $this->user_count) {
            $this->fake_user_count = $this->user_count;
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

        if (empty($this->summary)) {
            $this->summary = kg_parse_summary($this->details);
        }
    }

    public function afterCreate()
    {
        $cache = new MaxCourseIdCache();

        $cache->rebuild();

        $courseRating = new CourseRating();

        $courseRating->course_id = $this->id;

        if ($courseRating->create() === false) {
            throw new \RuntimeException('Create Course Rating Failed');
        }
    }

    public function afterFetch()
    {
        $this->market_price = (float)$this->market_price;
        $this->vip_price = (float)$this->vip_price;
        $this->rating = (float)$this->rating;
        $this->score = (float)$this->score;

        if (!Text::startsWith($this->cover, 'http')) {
            $this->cover = kg_cos_course_cover_url($this->cover);
        }

        if (is_string($this->tags)) {
            $this->tags = json_decode($this->tags, true);
        }

        if (is_string($this->attrs)) {
            $this->attrs = json_decode($this->attrs, true);
        }
    }

    public function getUserCount()
    {
        $userCount = $this->user_count;

        if ($this->fake_user_count > $userCount) {
            $userCount = $this->fake_user_count;
        }

        return $userCount;
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
            self::MODEL_OFFLINE => '面授',
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
            'featured' => '推荐',
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
            24 => '24个月',
            36 => '36个月',
            48 => '48个月',
        ];
    }

    public static function refundExpiryOptions()
    {
        return [
            0 => '0天',
            1 => '1天',
            3 => '3天',
            7 => '7天',
            14 => '14天',
            30 => '30天',
        ];
    }

}
