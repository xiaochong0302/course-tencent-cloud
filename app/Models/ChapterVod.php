<?php

namespace App\Models;

use App\Services\Vod as VodService;

class ChapterVod extends Model
{

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
     * 章节编号
     *
     * @var int
     */
    public $chapter_id = 0;

    /**
     * 文件编号
     *
     * @var string
     */
    public $file_id = '';

    /**
     * 文件转码
     *
     * @var array|string
     */
    public $file_transcode = [];

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
        return 'kg_chapter_vod';
    }

    public function beforeCreate()
    {
        if (is_array($this->file_transcode)) {
            $this->file_transcode = kg_json_encode($this->file_transcode);
        }

        $this->create_time = time();
    }

    public function beforeUpdate()
    {
        if (is_array($this->file_transcode)) {
            $this->file_transcode = kg_json_encode($this->file_transcode);
        }

        $this->update_time = time();
    }

    public function afterFetch()
    {
        if (is_string($this->file_transcode)) {
            $this->file_transcode = json_decode($this->file_transcode, true);
        }

        if (!empty($this->file_id) && empty($this->file_transcode)) {
            $this->file_transcode = $this->getFileTranscode($this->file_id);
        }
    }

    protected function getFileTranscode($fileId)
    {
        $vodService = new VodService();

        $transcode = $vodService->getFileTranscode($fileId);

        if ($transcode && empty($this->file_transcode)) {
            $this->file_transcode = $transcode;
            $this->update();
        }

        return $transcode;
    }

}