<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Page extends Model
{

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 标题
     *
     * @var string
     */
    public string $title = '';

    /**
     * 别名
     *
     * @var string
     */
    public string $alias = '';

    /**
     * 简介
     *
     * @var string
     */
    public string $summary = '';

    /**
     * 内容
     *
     * @var string
     */
    public string $content = '';

    /**
     * 关键字
     *
     * @var string
     */
    public string $keywords = '';

    /**
     * 发布标识
     *
     * @var int
     */
    public int $published = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

    /**
     * 浏览量
     *
     * @var int
     */
    public int $view_count = 0;

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

        $this->setSource('kg_page');

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

}
