<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Reply extends Model
{

    /**
     * @var array 引用属性
     */
    protected $_quote_attrs = [
        'author' => ['id' => 0, 'name' => ''],
        'content' => '',
        'created_at' => '',
    ];

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 作者编号
     *
     * @var int
     */
    public $author_id;

    /**
     * 主题编号
     *
     * @var int
     */
    public $thread_id;

    /**
     * 内容
     *
     * @var string
     */
    public $content;

    /**
     * 引用
     *
     * @var string
     */
    public $quote;

    /**
     * 点赞数量
     *
     * @var int
     */
    public $like_count;

    /**
     * 终端IP
     *
     * @var string
     */
    public $client_ip;

    /**
     * 终端类型
     *
     * @var string
     */
    public $client_type;

    /**
     * 发布标识
     *
     * @var int
     */
    public $published;

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
    public $created_at;

    /**
     * 更新时间
     *
     * @var int
     */
    public $updated_at;

    public function getSource()
    {
        return 'reply';
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
