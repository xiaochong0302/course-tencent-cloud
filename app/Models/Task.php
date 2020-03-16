<?php

namespace App\Models;

class Task extends Model
{

    /**
     * 任务类型
     */
    const TYPE_REFUND = 'refund'; // 退款
    const TYPE_PROCESS_ORDER = 'process_order'; // 处理订单
    const TYPE_LIVE_NOTIFY = 'live_notify'; // 直播通知

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
    public $created_at;

    /**
     * 更新时间
     *
     * @var int
     */
    public $updated_at;

    public function getSource()
    {
        return 'kg_task';
    }

    public function beforeCreate()
    {
        $this->status = self::STATUS_PENDING;

        $this->created_at = time();

        if (!empty($this->item_info)) {
            $this->item_info = kg_json_encode($this->item_info);
        } else {
            $this->item_info = '';
        }
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();

        if (!empty($this->item_info)) {
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
