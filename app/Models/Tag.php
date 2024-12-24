<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use App\Caches\MaxTagId as MaxTagIdCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Text;

class Tag extends Model
{

    /**
     * 范围类型
     */
    const SCOPE_ARTICLE = 1;
    const SCOPE_QUESTION = 2;
    const SCOPE_COURSE = 3;

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

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
     * 范围
     *
     * @var array|string
     */
    public $scopes = 'all';

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
    public $published = 1;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 关注数量
     *
     * @var int
     */
    public $follow_count = 0;

    /**
     * 课程数量
     *
     * @var int
     */
    public $course_count = 0;

    /**
     * 文章数量
     *
     * @var int
     */
    public $article_count = 0;

    /**
     * 问题数量
     *
     * @var int
     */
    public $question_count = 0;

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
        return 'kg_tag';
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

        if (is_array($this->scopes)) {
            $this->scopes = kg_json_encode($this->scopes);
        }
    }

    public function afterCreate()
    {
        $cache = new MaxTagIdCache();

        $cache->rebuild();
    }

    public function afterFetch()
    {
        if (!Text::startsWith($this->icon, 'http')) {
            $this->icon = kg_cos_category_icon_url($this->icon);
        }

        if (is_string($this->scopes) && $this->scopes != 'all') {
            $this->scopes = json_decode($this->scopes, true);
        }
    }

    public static function getIconPath($url)
    {
        if (Text::startsWith($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
    }

    public static function scopeTypes()
    {
        return [
            self::SCOPE_ARTICLE => '文章',
            self::SCOPE_QUESTION => '问答',
            self::SCOPE_COURSE => '课程',
        ];
    }

}
