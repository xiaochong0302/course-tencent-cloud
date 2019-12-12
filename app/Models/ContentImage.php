<?php

namespace App\Models;

class ContentImage extends Model
{

    /**
     * 主键编号
     * 
     * @var integer
     */
    public $id;

    /**
     * 图片路径
     *
     * @var string
     */
    public $path;

    /**
     * 创建时间
     * 
     * @var integer
     */
    public $created_at;

    public function getSource()
    {
        return 'content_image';
    }

    public function beforeCreate()
    {
        $this->created_at = time();
    }

}
