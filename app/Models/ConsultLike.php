<?php

namespace App\Models;

class ConsultLike extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 咨询编号
     *
     * @var int
     */
    public $consult_id = 0;

    /**
     * 用户编号
     *
     * @var int
     */
    public $user_id = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time = 0;

    public function getSource(): string
    {
        return 'kg_consult_like';
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

}