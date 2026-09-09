<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class Learning extends Model
{

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 请求编号
     *
     * @var string
     */
    public string $request_id = '';

    /**
     * 课程编号
     *
     * @var int
     */
    public int $course_id = 0;

    /**
     * 章节编号
     *
     * @var int
     */
    public int $chapter_id = 0;

    /**
     * 用户编号
     *
     * @var int
     */
    public int $user_id = 0;

    /**
     * 计划编号
     *
     * @var int
     */
    public int $plan_id = 0;

    /**
     * 持续时长（秒）
     *
     * @var int
     */
    public int $duration = 0;

    /**
     * 播放位置（秒）
     *
     * @var int
     */
    public int $position = 0;

    /**
     * 客户端类型
     *
     * @var int
     */
    public int $client_type = 0;

    /**
     * 客户端IP
     *
     * @var string
     */
    public string $client_ip = '';

    /**
     * 活跃时间
     *
     * @var int
     */
    public int $active_time = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public int $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public int $update_time = 0;

    public function initialize(): void
    {
        parent::initialize();

        $this->setSource('kg_learning');
    }

    public function beforeCreate(): void
    {
        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        $this->update_time = time();
    }

}
