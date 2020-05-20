<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Text;

class Slide extends Model
{

    /**
     * 目标类型
     */
    const TARGET_COURSE = 'course'; // 课程
    const TARGET_PAGE = 'page'; // 单页
    const TARGET_LINK = 'link'; // 链接

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
     * 样式
     *
     * @var string
     */
    public $style;

    /**
     * 摘要
     *
     * @var string
     */
    public $summary;

    /**
     * 目标
     *
     * @var string
     */
    public $target;

    /**
     * 内容
     *
     * @var string
     */
    public $content;

    /**
     * 优先级
     *
     * @var int
     */
    public $priority;

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

    public function getSource()
    {
        return 'kg_slide';
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

        if (Text::startsWith($this->cover, 'http')) {
            $this->cover = self::getCoverPath($this->cover);
        }

        if (is_array($this->style) && !empty($this->style)) {
            $this->style = kg_json_encode($this->style);
        }
    }

    public function beforeUpdate()
    {
        $this->update_time = time();

        if (Text::startsWith($this->cover, 'http')) {
            $this->cover = self::getCoverPath($this->cover);
        }

        if (!empty($this->style)) {
            $this->style = kg_json_encode($this->style);
        }
    }

    public function afterFetch()
    {
        if (!Text::startsWith($this->cover, 'http')) {
            $this->cover = kg_ci_cover_img_url($this->cover);
        }

        if (!empty($this->style)) {
            $this->style = json_decode($this->style, true);
        }
    }

    public static function htmlStyle($style)
    {
        $result = [];

        if (isset($style['bg_color'])) {
            $result[] = "background-color:{$style['bg_color']}";
        }

        return implode(';', $result);
    }

    public static function getCoverPath($url)
    {
        if (Text::startsWith($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
    }

    public static function targetTypes()
    {
        return [
            self::TARGET_COURSE => '课程',
            self::TARGET_PAGE => '单页',
            self::TARGET_LINK => '链接',
        ];
    }

}
