<?php

namespace App\Models;

class Task extends Model
{

    /**
     * 任务类型
     */
    const TYPE_REFUND = 'refund'; // 退款
    const TYPE_ORDER = 'order'; // 下单

    /**
     * 优先级
     */
    const PRIORITY_HIGH = 10; // 高
    const PRIORITY_MIDDLE = 20; // 中
    const PRIORITY_LOW = 30; // 低

    /**
     * 状态类型
     */
    const STATUS_PENDING = 'pending'; // 待定
    const STATUS_FINISHED = 'finished'; // 完成
    const STATUS_CANCELED = 'canceled'; // 取消
    const STATUS_FAILED = 'failed'; // 失败

    /**
     * 主键编号
     *
     * @var int
     */
    public $id;

    /**
     * 条目编号
     *
     * @var string
     */
    public $item_id;

    /**
     * 条目类型
     *
     * @var string
     */
    public $item_type;

    /**
     * 条目内容
     *
     * @var string|array
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
     * @var string
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
    public $create_time;

    /**
     * 更新时间
     *
     * @var int
     */
    public $update_time;

    public function getSource()
    {
        return 'kg_task';
    }

    public function beforeCreate()
    {
        $this->status = self::STATUS_PENDING;

        $this->create_time = time();

        if (!empty($this->item_info)) {
            $this->item_info = kg_json_encode($this->item_info);
        } else {
            $this->item_info = ''; // text类型不能填充默认值
        }
    }

    public function beforeUpdate()
    {
        $this->update_time = time();

        if (is_array($this->item_info)) {
            $this->item_info = kg_json_encode($this->item_info);
        }
    }

    public function afterFetch()
    {
        if (!empty($this->item_info) && is_string($this->item_info)) {
            $this->item_info = json_decode($this->item_info, true);
        }
    }

}
