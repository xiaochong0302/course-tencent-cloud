<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class ContentImage extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 图片路径
     *
     * @var string
     */
    public $path;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted;

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
        return 'kg_content_image';
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
        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        $this->update_at = time();
    }

}
