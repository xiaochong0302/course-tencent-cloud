<?php

namespace App\Models;

class Audit extends Model
{

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 用户编号
     *
     * @var integer
     */
    public $user_id;

    /**
     * 用户名称
     *
     * @var integer
     */
    public $user_name;

    /**
     * 用户IP
     *
     * @var integer
     */
    public $user_ip;

    /**
     * 请求路由
     *
     * @var string
     */
    public $req_route;

    /**
     * 请求路径
     *
     * @var string
     */
    public $req_path;

    /**
     * 请求参数
     *
     * @var string
     */
    public $req_data;

    /**
     * 创建时间
     *
     * @var integer
     */
    public $created_at;

    public function getSource()
    {
        return 'audit';
    }

    public function beforeCreate()
    {
        $this->created_at = time();

        if (!empty($this->req_data) && is_array($this->req_data)) {
            $this->req_data = kg_json_encode($this->req_data);
        } else {
            $this->req_data = '';
        }
    }

}
