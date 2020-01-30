<?php

namespace App\Models;

class Task extends Model
{

    /**
     * 任务类型
     */
    const TYPE_REFUND = 'refund';

    /**
     * 优先级
     */
    const PRIORITY_HIGH = 1;
    const PRIORITY_MIDDLE = 2;
    const PRIORITY_LOW = 3;

    /**
     * 状态类型
     */
    const STATUS_PENDING = 'pending';
    const STATUS_FINISHED = 'finished';
    const STATUS_FAILED = 'failed';

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 条目编号
     *
     * @var int
     */
    public $item_id;

    /**
     * 条目类型
     *
     * @var int
     */
    public $item_type;

    /**
     * 条目内容
     *
     * @var string
     */
    public $item_info;

    /**
     * 优先级
     *
     * @var int
     */
    public $priority;

    /**
     * 状态标识
     *
     * @var int
     */
    public $status;

    /**
     * 重试次数
     *
     * @var int
     */
    public $try_count;

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
        return 'task';
    }

    public function beforeCreate()
    {
        $this->created_at = time();

        $this->status = self::STATUS_PENDING;

        if (is_array($this->item_info) && !empty($this->item_info)) {
            $this->item_info = kg_json_encode($this->item_info);
        } else {
            $this->item_info = '';
        }
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();

        if (is_array($this->item_info) && !empty($this->item_info)) {
            $this->item_info = kg_json_encode($this->item_info);
        }
    }

    public function afterFetch()
    {
        if (!empty($this->item_info)) {
            $this->item_info = json_decode($this->item_info, true);
        }
    }

}
