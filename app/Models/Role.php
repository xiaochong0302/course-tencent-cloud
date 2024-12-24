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
    const TYPE_SYSTEM = 1; // 内置
    const TYPE_CUSTOM = 2; // 自定

    /**
     * 内置角色
     */
    const ROLE_ROOT = 1; // 管理人员
    const ROLE_OPERATOR = 2; // 运营人员
    const ROLE_EDITOR = 3; // 编辑人员
    const ROLE_FINANCE = 4; // 财务人员

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 类型
     *
     * @var int
     */
    public $type = self::TYPE_CUSTOM;

    /**
     * 名称
     *
     * @var string
     */
    public $name = '';

    /**
     * 简介
     *
     * @var string
     */
    public $summary = '';

    /**
     * 权限路由
     *
     * @var array|string
     */
    public $routes = [];

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 成员数
     *
     * @var int
     */
    public $user_count = 0;

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
        return 'kg_role';
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
        if (is_array($this->routes)) {
            $this->routes = kg_json_encode($this->routes);
        }
    }

    public function afterFetch()
    {
        if (is_string($this->routes)) {
            $this->routes = json_decode($this->routes, true);
        }
    }

    public static function types()
    {
        return [
            self::TYPE_SYSTEM => '内置',
            self::TYPE_CUSTOM => '自定',
        ];
    }

    public static function sysRoleTypes()
    {
        return [
            self::ROLE_ROOT => '管理人员',
            self::ROLE_OPERATOR => '运营人员',
            self::ROLE_EDITOR => '编辑人员',
            self::ROLE_FINANCE => '财务人员',
        ];
    }

}
