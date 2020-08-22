<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Upload extends Model
{

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 名称
     *
     * @var string
     */
    public $name;

    /**
     * 路径
     *
     * @var string
     */
    public $path;

    /**
     * mime
     *
     * @var string
     */
    public $mime;

    /**
     * md5
     *
     * @var string
     */
    public $md5;

    /**
     * 大小
     *
     * @var int
     */
    public $size;

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
        return 'kg_upload';
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
        $this->update_time = time();
    }

}
