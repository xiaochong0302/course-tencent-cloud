<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class ChapterRead extends Model
{

    /**
     * 图文设置
     *
     * @var array
     */
    protected array $_settings = [
        'comment_enabled' => 1,
    ];

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
     * 章节编号
     *
     * @var int
     */
    public int $chapter_id = 0;

    /**
     * 内容 (html)
     *
     * @var string
     */
    public string $content = '';

    /**
     * 图文设置
     *
     * @var array|string
     */
    public array|string $settings = [];

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

        $this->setSource('kg_chapter_read');
    }

    public function beforeCreate(): void
    {
        $this->settings = $this->settings ?: $this->_settings;

        if (is_array($this->settings)) {
            $this->settings = kg_json_encode($this->settings);
        }

        $this->create_time = time();
    }

    public function beforeUpdate(): void
    {
        if (is_array($this->settings)) {
            $this->settings = kg_json_encode($this->settings);
        }

        $this->update_time = time();
    }

    public function afterFetch(): void
    {
        if (is_string($this->settings)) {
            $this->settings = json_decode($this->settings, true);
        }
    }

}
