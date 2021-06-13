<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class QuestionTag extends Model
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
     * 标签编号
     *
     * @var int
     */
    public $tag_id = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time = 0;

    public function getSource(): string
    {
        return 'kg_question_tag';
    }

    public function beforeCreate()
    {
        $this->create_time = time();
    }

}
