<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use App\Caches\MaxCategoryId as MaxCategoryIdCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Text;

class Category extends Model
{

    /**
     * 类型
     */
    const TYPE_COURSE = 1; // 课程
    const TYPE_HELP = 2; // 帮助
    const TYPE_ARTICLE = 3; // 文章
    const TYPE_QUESTION = 4; // 问答

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 上级编号
     *
     * @var int
     */
    public $parent_id = 0;

    /**
     * 层级
     *
     * @var int
     */
    public $level = 0;

    /**
     * 类型
     *
     * @var int
     */
    public $type = 0;

    /**
     * 名称
     *
     * @var string
     */
    public $name = '';

    /**
     * 别名
     *
     * @var string
     */
    public $alias = '';

    /**
     * 图标
     *
     * @var string
     */
    public $icon = '';

    /**
     * 路径
     *
     * @var string
     */
    public $path = '';

    /**
     * 优先级
     *
     * @var int
     */
    public $priority = 10;

    /**
     * 发布标识
     *
     * @var int
     */
    public $published = 1;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 节点数
     *
     * @var int
     */
    public $child_count = 0;

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
        return 'kg_category';
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
        if (empty($this->icon)) {
            $this->icon = kg_default_category_icon_path();
        } elseif (Text::startsWith($this->icon, 'http')) {
            $this->icon = self::getIconPath($this->icon);
        }
    }

    public function afterCreate()
    {
        $cache = new MaxCategoryIdCache();

        $cache->rebuild();
    }

    public function afterFetch()
    {
        if (!Text::startsWith($this->icon, 'http')) {
            $this->icon = kg_cos_category_icon_url($this->icon);
        }
    }

    public static function getIconPath($url)
    {
        if (Text::startsWith($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
    }

    public static function types()
    {
        return [
            self::TYPE_COURSE => '课程',
            self::TYPE_HELP => '帮助',
            self::TYPE_ARTICLE => '专栏',
            self::TYPE_QUESTION => '问答',
        ];
    }

}
