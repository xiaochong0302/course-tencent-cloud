<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class ChapterVod extends Model
{

    /**
     * 点播设置
     *
     * @var array
     */
    protected array $_settings = [
        'comment_enabled' => 1,
        'danmu_enabled' => 1,
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
     * 文件编号
     *
     * @var string
     */
    public string $file_id = '';

    /**
     * 原始文件
     *
     * @var array|string
     */
    public array|string $file_origin = [];

    /**
     * 常规转码
     *
     * @var array|string
     */
    public array|string $file_transcode = [];

    /**
     * 加密转码
     *
     * @var array|string
     */
    public array|string $file_encrypt = [];

    /**
     * 远程资源
     *
     * @var array|string
     */
    public array|string $file_remote = [];

    /**
     * 点播设置
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

        $this->setSource('kg_chapter_vod');
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

    public function beforeSave(): void
    {
        if (is_array($this->file_origin)) {
            $this->file_origin = kg_json_encode($this->file_origin);
        }

        if (is_array($this->file_transcode)) {
            $this->file_transcode = kg_json_encode($this->file_transcode);
        }

        if (is_array($this->file_encrypt)) {
            $this->file_encrypt = kg_json_encode($this->file_encrypt);
        }

        if (is_array($this->file_remote)) {
            $this->file_remote = kg_json_encode($this->file_remote);
        }
    }

    public function afterFetch(): void
    {
        if (is_string($this->file_origin)) {
            $this->file_origin = json_decode($this->file_origin, true);
        }

        if (is_string($this->file_transcode)) {
            $this->file_transcode = json_decode($this->file_transcode, true);
        }

        if (is_string($this->file_encrypt)) {
            $this->file_encrypt = json_decode($this->file_encrypt, true);
        }

        if (is_string($this->file_remote)) {
            $this->file_remote = json_decode($this->file_remote, true);
        }

        if (is_string($this->settings)) {
            $this->settings = json_decode($this->settings, true);
        }
    }

}
