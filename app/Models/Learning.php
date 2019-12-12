<?php

namespace App\Models;

class Learning extends Model
{

    /**
     * 客户端类型
     */
    const CLIENT_DESKTOP = 'desktop';
    const CLIENT_MOBILE = 'mobile';

    /**
     * 主键编号
     * 
     * @var integer
     */
    public $id;

    /**
     * 请求编号
     *
     * @var string
     */
    public $request_id;

    /**
     * 用户编号
     *
     * @var integer
     */
    public $user_id;

    /**
     * 课程编号
     *
     * @var integer
     */
    public $course_id;

    /**
     * 章节编号
     *
     * @var integer
     */
    public $chapter_id;

    /**
     * 持续时长
     *
     * @var integer
     */
    public $duration;

    /**
     * 播放位置
     *
     * @var integer
     */
    public $position;

    /**
     * 客户端类型
     *
     * @var integer
     */
    public $client_type;

    /**
     * 客户端IP
     * 
     * @var string
     */
    public $client_ip;

    /**
     * 所在国家
     *
     * @var string
     */
    public $country;

    /**
     * 所在省份
     *
     * @var string
     */
    public $province;

    /**
     * 所在城市
     *
     * @var string
     */
    public $city;

    /**
     * 创建时间
     * 
     * @var integer
     */
    public $created_at;

    /**
     * 更新时间
     * 
     * @var integer
     */
    public $updated_at;

    public function getSource()
    {
        return 'learning';
    }

    public function beforeCreate()
    {
        $this->created_at = time();
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();
    }

}
