<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use App\Caches\MaxCategoryId as MaxCategoryIdCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Category extends Model
{

    /**
     * 类型
     */
    const int TYPE_COURSE = 1; // 课程
    const int TYPE_HELP = 2; // 帮助
    const int TYPE_ARTICLE = 3; // 文章
    const int TYPE_QUESTION = 4; // 问答
    const int TYPE_EXAM_PAPER = 5; // 试卷
    const int TYPE_EXAM_QUESTION = 6; // 题库

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 上级编号
     *
     * @var int
     */
    public int $parent_id = 0;

    /**
     * 层级
     *
     * @var int
     */
    public int $level = 0;

    /**
     * 类型
     *
     * @var int
     */
    public int $type = 0;

    /**
     * 名称
     *
     * @var string
     */
    public string $name = '';

    /**
     * 别名
     *
     * @var string
     */
    public string $alias = '';

    /**
     * 图标
     *
     * @var string
     */
    public string $icon = '';

    /**
     * 路径
     *
     * @var string
     */
    public string $path = '';

    /**
     * 优先级
     *
     * @var int
     */
    public int $priority = 10;

    /**
     * 发布标识
     *
     * @var int
     */
    public int $published = 1;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

    /**
     * 节点数
     *
     * @var int
     */
    public int $child_count = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public int $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public int $update_time = 0;

    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('kg_category');

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate(): void
    {
        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        $this->update_time = time();
    }

    public function afterCreate(): void
    {
        $cache = new MaxCategoryIdCache();

        $cache->rebuild();
    }

    public static function types(): array
    {
        return [
            self::TYPE_COURSE => '课程',
            self::TYPE_HELP => '帮助',
            self::TYPE_ARTICLE => '专栏',
            self::TYPE_QUESTION => '问答',
            self::TYPE_EXAM_PAPER => '试卷',
            self::TYPE_EXAM_QUESTION => '题库',
        ];
    }

}
