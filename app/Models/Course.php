<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Course extends Model
{

    /**
     * 模型
     */
    const MODEL_VOD = 'vod'; // 点播
    const MODEL_LIVE = 'live'; // 直播
    const MODEL_ARTICLE = 'article'; // 图文

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
    protected $_article_attrs = ['word_count' => 0];

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 作者编号
     *
     * @var integer
     */
    public $user_id;

    /**
     * 类型
     *
     * @var string
     */
    public $model;

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
     * @var integer
     */
    public $expiry;

    /**
     * 综合得分
     *
     * @var float
     */
    public $score;

    /**
     * 难度级别
     *
     * @var string
     */
    public $level;

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
     * 扩展属性
     *
     * @var string
     */
    public $attrs;

    /**
     * 课时数
     *
     * @var integer
     */
    public $lesson_count;

    /**
     * 学员数
     *
     * @var integer
     */
    public $student_count;

    /**
     * 讨论数
     *
     * @var integer
     */
    public $thread_count;

    /**
     * 评价数
     *
     * @var integer
     */
    public $review_count;

    /**
     * 收藏数
     *
     * @var integer
     */
    public $favorite_count;

    /**
     * 创建时间
     *
     * @var integer
     */
    public $created_at;

    /**
     * 更新时间
     *
     * @var integer
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
            case Course::MODEL_ARTICLE:
                $attrs = $this->_article_attrs;
                break;
        }

        $this->attrs = $attrs ? kg_json_encode($attrs) : '';
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
        if (!empty($this->attrs)) {
            $this->attrs = json_decode($this->attrs);
        }
    }

    public static function models()
    {
        $list = [
            self::MODEL_VOD => '点播',
            self::MODEL_LIVE => '直播',
            self::MODEL_ARTICLE => '图文',
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
