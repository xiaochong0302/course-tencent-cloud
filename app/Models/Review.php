<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Review extends Model
{

    /**
     * 发布状态
     */
    const int PUBLISH_PENDING = 1; // 审核中
    const int PUBLISH_APPROVED = 2; // 已发布
    const int PUBLISH_REJECTED = 3; // 未通过

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 课程编号
     *
     * @var int
     */
    public int $course_id = 0;

    /**
     * 用户编号
     *
     * @var int
     */
    public int $owner_id = 0;

    /**
     * 终端类型
     *
     * @var int
     */
    public int $client_type = 0;

    /**
     * 终端IP
     *
     * @var string
     */
    public string $client_ip = '';

    /**
     * 评价内容
     *
     * @var string
     */
    public string $content = '';

    /**
     * 回复内容
     *
     * @var string
     */
    public string $reply = '';

    /**
     * 综合评分
     *
     * @var float
     */
    public float $rating = 0.00;

    /**
     * 维度1评分
     *
     * @var float
     */
    public float $rating1 = 0.00;

    /**
     * 维度2评分
     *
     * @var float
     */
    public float $rating2 = 0.00;

    /**
     * 维度3评分
     *
     * @var float
     */
    public float $rating3 = 0.00;

    /**
     * 匿名标识
     *
     * @var int
     */
    public int $anonymous = 0;

    /**
     * 置顶标识
     *
     * @var int
     */
    public int $sticky = 0;

    /**
     * 发布标识
     *
     * @var int
     */
    public int $published = self::PUBLISH_PENDING;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

    /**
     * 点赞数量
     *
     * @var int
     */
    public int $like_count = 0;

    /**
     * 举报数
     *
     * @var int
     */
    public int $report_count = 0;

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

        $this->setSource('kg_review');

        $this->addBehavior(
            new SoftDelete([
                'field' => 'deleted',
                'value' => 1,
            ])
        );
    }

    public function beforeCreate(): void
    {
        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        $this->update_time = time();
    }

    public function beforeSave(): void
    {
        $this->rating = $this->getAvgRating();
    }

    protected function getAvgRating(): float
    {
        $sumRating = $this->rating1 + $this->rating2 + $this->rating3;

        return round($sumRating / 3, 2);
    }

    public static function publishTypes(): array
    {
        return [
            self::PUBLISH_PENDING => '审核中',
            self::PUBLISH_APPROVED => '已发布',
            self::PUBLISH_REJECTED => '未通过',
        ];
    }

}
