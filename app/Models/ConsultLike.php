<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class ConsultLike extends Model
{

    /**
     * 主键编号
     *
     * @var integer
     */
    public $id;

    /**
     * 咨询编号
     *
     * @var integer
     */
    public $consult_id;

    /**
     * 用户编号
     *
     * @var integer
     */
    public $user_id;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted;

    /**
     * 创建时间
     *
     * @var integer
     */
    public $created_at;

    public function getSource()
    {
        return 'consult_like';
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
