<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\SoftDelete;

class Slide extends Model
{

    /**
     * 目标类型
     */
    const int TARGET_COURSE = 1; // 课程
    const int TARGET_PAGE = 2; // 单页
    const int TARGET_LINK = 3; // 链接
    const int TARGET_EXAM_PAPER = 4; // 试卷
    const int TARGET_ARTICLE = 5; // 专栏
    const int TARGET_PACKAGE = 6; // 套餐
    const int TARGET_VIP = 7; // 会员

    /**
     * 主键编号
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * 标题
     *
     * @var string
     */
    public string $title = '';

    /**
     * 封面
     *
     * @var string
     */
    public string $cover = '';

    /**
     * 摘要
     *
     * @var string
     */
    public string $summary = '';

    /**
     * 平台
     *
     * @var int
     */
    public int $platform = 0;

    /**
     * 优先级
     *
     * @var int
     */
    public int $priority = 10;

    /**
     * 目标编号
     *
     * @var int
     */
    public int $target_id = 0;

    /**
     * 目标类型
     *
     * @var int
     */
    public int $target_type = 0;
    
    /**
     * 目标信息
     *
     * @var array|string
     */
    public array|string $target_info = [];

    /**
     * 发布标识
     *
     * @var int
     */
    public int $published = 0;

    /**
     * 删除标识
     *
     * @var int
     */
    public int $deleted = 0;

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

        $this->setSource('kg_slide');

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
        if (empty($this->cover)) {
            $this->cover = kg_default_slide_cover_path();
        } elseif (str_starts_with($this->cover, 'http')) {
            $this->cover = self::getCoverPath($this->cover);
        }

        if (is_array($this->target_info)) {
            $this->target_info = kg_json_encode($this->target_info);
        }
    }

    public function afterFetch(): void
    {
        if (!str_starts_with($this->cover, 'http')) {
            $this->cover = kg_cos_slide_cover_url($this->cover);
        }

        if (is_string($this->target_info)) {
            $this->target_info = json_decode($this->target_info, true);
        }
    }

    public static function getCoverPath(string $url): string
    {
        if (str_starts_with($url, 'http')) {
            return parse_url($url, PHP_URL_PATH);
        }

        return $url;
    }

    public static function targetTypes(): array
    {
        return [
            self::TARGET_COURSE => '课程',
            self::TARGET_VIP => '会员',
            self::TARGET_PAGE => '单页',
            self::TARGET_LINK => '链接',
        ];
    }

}
