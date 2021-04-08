<?php

namespace App\Models;

use App\Caches\MaxTagId as MaxTagIdCache;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Tag extends Model
{

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
        if ($this->deleted == 1) {
            $this->published = 0;
        }

        $this->update_time = time();
    }

    public function afterCreate()
    {
        $cache = new MaxTagIdCache();

        $cache->rebuild();
    }

}