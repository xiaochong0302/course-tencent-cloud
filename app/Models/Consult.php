<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Consult extends Model
{

    /**
     * 发布状态
     */
    const PUBLISH_PENDING = 1; // 审核中
    const PUBLISH_APPROVED = 2; // 已发布
    const PUBLISH_REJECTED = 3; // 未通过

    /**
     * 优先级
     */
    const PRIORITY_HIGH = 10; // 高
    const PRIORITY_MIDDLE = 20; // 中
    const PRIORITY_LOW = 30; // 低

    /**
     * 主键编号
     *
     * @var int
     */
    public $id = 0;

    /**
     * 课程编号
     *
     * @var int
     */
    public $course_id = 0;

    /**
     * 提主编号
     *
     * @var int
     */
    public $owner_id = 0;

    /**
     * 答主编号
     *
     * @var int
     */
    public $replier_id = 0;

    /**
     * 终端类型
     *
     * @var integer
     */
    public $client_type = 0;

    /**
     * 终端IP
     *
     * @var string
     */
    public $client_ip = '';

    /**
     * 提问
     *
     * @var string
     */
    public $question = '';

    /**
     * 回答
     *
     * @var string
     */
    public $answer = '';

    /**
     * 评分
     *
     * @var int
     */
    public $rating = 0;

    /**
     * 优先级
     *
     * @var int
     */
    public $priority = self::PRIORITY_LOW;

    /**
     * 私密标识
     *
     * @var int
     */
    public $private = 0;

    /**
     * 发布标识
     *
     * @var int
     */
    public $published = self::PUBLISH_PENDING;

    /**
     * 删除标识
     *
     * @var int
     */
    public $deleted = 0;

    /**
     * 赞成数
     *
     * @var int
     */
    public $like_count = 0;

    /**
     * 举报数
     *
     * @var integer
     */
    public $report_count;

    /**
     * 回复时间
     *
     * @var int
     */
    public $reply_time = 0;

    /**
     * 创建时间
     *
     * @var int
     */
    public $create_time = 0;

    /**
     * 更新时间
     *
     * @var int
     */
    public $update_time = 0;

    public function getSource(): string
    {
        return 'kg_consult';
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

    public static function publishTypes()
    {
        return [
            self::PUBLISH_PENDING => '审核中',
            self::PUBLISH_APPROVED => '已发布',
            self::PUBLISH_REJECTED => '未通过',
        ];
    }

}
