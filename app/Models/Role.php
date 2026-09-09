<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Role extends Model
{

    /**
     * 角色类型
     */
    const int TYPE_SYSTEM = 1; // 内置
    const int TYPE_CUSTOM = 2; // 自定

    /**
     * 内置角色
     */
    const int ROLE_ROOT = 1; // 管理人员
    const int ROLE_OPERATOR = 2; // 运营人员
    const int ROLE_EDITOR = 3; // 编辑人员
    const int ROLE_FINANCE = 4; // 财务人员

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 类型
     *
     * @var int
     */
    public int $type = self::TYPE_CUSTOM;

    /**
     * 名称
     *
     * @var string
     */
    public string $name = '';

    /**
     * 简介
     *
     * @var string
     */
    public string $summary = '';

    /**
     * 权限路由
     *
     * @var array|string
     */
    public array|string $routes = [];

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

    /**
     * 成员数
     *
     * @var int
     */
    public int $user_count = 0;

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

        $this->setSource('kg_role');

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

    public function beforeSave(): void
    {
        if (is_array($this->routes)) {
            $this->routes = kg_json_encode($this->routes);
        }
    }

    public function afterFetch(): void
    {
        if (is_string($this->routes)) {
            $this->routes = json_decode($this->routes, true);
        }
    }

}
