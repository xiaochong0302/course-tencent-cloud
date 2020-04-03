<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Role extends Model
{

    /**
     * 角色类型
     */
    const TYPE_SYSTEM = 'system'; // 内置
    const TYPE_CUSTOM = 'custom'; // 自定

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
    public $id;

    /**
     * 类型
     *
     * @var string
     */
    public $type;

    /**
     * 名称
     *
     * @var string
     */
    public $name;

    /**
     * 简介
     *
     * @var string
     */
    public $summary;

    /**
     * 权限路由
     *
     * @var string
     */
    public $routes;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted;

    /**
     * 成员数
     *
     * @var int
     */
    public $user_count;

    /**
     * 创建时间
     *
     * @var int
     */
    public $created_at;

    /**
     * 更新时间
     *
     * @var int
     */
    public $updated_at;

    public function getSource()
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
        $this->created_at = time();

        if (!empty($this->routes)) {
            $this->routes = kg_json_encode($this->routes);
        }
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();

        if (!empty($this->routes)) {
            $this->routes = kg_json_encode($this->routes);
        }
    }

    public function afterFetch()
    {
        if (!empty($this->routes)) {
            $this->routes = json_decode($this->routes);
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
