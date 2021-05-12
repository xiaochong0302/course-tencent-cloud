<?php

namespace App\Models;

class AnswerLike extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 回答编号
     *
     * @var int
     */
    public $answer_id = 0;

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
        return 'kg_answer_like';
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

}