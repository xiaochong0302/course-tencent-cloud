<?php

namespace App\Models;

class ImMessageContent extends Model
{

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 内容
     *
     * @var string
     */
    public $content;

    public function getSource()
    {
        return 'kg_im_message_content';
    }

}
