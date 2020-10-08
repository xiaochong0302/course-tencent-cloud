<?php

namespace App\Models;

class Online extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 用户编号
     *
     * @var int
     */
    public $user_id;

    /**
     * 计划编号
     *
     * @var string
     */
    public $date;

    /**
     * 客户端类型
     *
     * @var int
     */
    public $client_type;

    /**
     * 客户端IP
     *
     * @var string
     */
    public $client_ip;

    /**
     * 活跃时间
     *
     * @var int
     */
    public $active_time;

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

    public function getSource(): string
    {
        return 'kg_online';
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->update_time = time();
    }

}
