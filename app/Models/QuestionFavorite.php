<?php

namespace App\Models;

class QuestionFavorite extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 问题编号
     *
     * @var int
     */
    public $question_id = 0;

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
        return 'kg_question_favorite';
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

}