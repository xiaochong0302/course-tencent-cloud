<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Page extends Model
{

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 标题
     *
     * @var string
     */
    public $title;

    /**
     * 内容
     *
     * @var string
     */
    public $content;

    /**
     * 删除标识
     *
     * @var integer
     */
    public $deleted;

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
        return 'page';
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
        $this->created_at = time();
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();
    }

}
