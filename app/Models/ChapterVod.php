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
    public $id;

    /**
     * 课程编号
     *
     * @var int
     */
    public $course_id;

    /**
     * 章节编号
     *
     * @var int
     */
    public $chapter_id;

    /**
     * 文件编号
     *
     * @var string
     */
    public $file_id;

    /**
     * 文件转码
     *
     * @var string
     */
    public $file_transcode;

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
        return 'kg_chapter_vod';
    }

    public function beforeCreate()
    {
        $this->create_time = time();

        if (!empty($this->file_transcode)) {
            $this->file_transcode = kg_json_encode($this->file_transcode);
        }
    }

    public function beforeUpdate()
    {
        $this->update_time = time();

        if (!empty($this->file_transcode)) {
            $this->file_transcode = kg_json_encode($this->file_transcode);
        }
    }

    public function afterFetch()
    {
        if (!empty($this->file_transcode)) {
            $this->file_transcode = json_decode($this->file_transcode, true);
        } else {
            $this->getFileTranscode($this->file_id);
        }
    }

    protected function getFileTranscode($fileId)
    {
        if (!$fileId) return [];

        $vodService = new VodService();

        $transcode = $vodService->getFileTranscode($fileId);

        if ($transcode && empty($this->file_transcode)) {

            $this->file_transcode = $transcode;

            $this->update();

            /**
             * afterUpdate事件触发序列化，故重设属性
             */
            $this->file_transcode = $transcode;
        }

        return $transcode;
    }

}
