<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Text;

class Slide extends Model
{

    /**
     * 目标类型
     */
    const TARGET_COURSE = 1; // 课程
    const TARGET_PAGE = 2; // 单页
    const TARGET_LINK = 3; // 链接

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
     * 摘要
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
     * 平台
     *
     * @var int
     */
    public $platform = 0;

    /**
     * 目标类型
     *
     * @var int
     */
    public $target = 0;

    /**
     * 目标属性
     *
     * @var array
     */
    public $target_attrs = [];

    /**
     * 优先级
     *
     * @var int
     */
    public $priority = 100;

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
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

    public function beforeSave()
    {
        if (empty($this->cover)) {
            $this->cover = kg_default_slide_cover_path();
        } elseif (Text::startsWith($this->cover, 'http')) {
            $this->cover = self::getCoverPath($this->cover);
        }

        if (is_array($this->target_attrs)) {
            $this->target_attrs = kg_json_encode($this->target_attrs);
        }
    }

    public function afterFetch()
    {
        if (!Text::startsWith($this->cover, 'http')) {
            $this->cover = kg_cos_slide_cover_url($this->cover);
        }

        if (is_string($this->target_attrs)) {
            $this->target_attrs = json_decode($this->target_attrs, true);
        }
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
