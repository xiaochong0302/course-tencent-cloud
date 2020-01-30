<?php

namespace App\Builders;

use App\Models\Course as CourseModel;

class CourseChapterUser extends Builder
{


    /**
     * 处理课时进度
     *
     * @param array $chapters
     * @param array $studyHistory
     * @return array
     */
    public function handleProcess($chapters, $studyHistory)
    {
        $status = [];

        if ($studyHistory) {
            foreach ($studyHistory as $value) {
                $status[$value['chapter_id']] = [
                    'duration' => $value['duration'],
                    'finished' => $value['finished'],
                ];
            }
        }

        foreach ($chapters as $key => $chapter) {
            if ($chapter['parent_id'] > 0) {
                $me = [
                    'duration' => $status[$chapter['id']]['duration'] ?? 0,
                    'finished' => $status[$chapter['id']]['finished'] ?? 0,
                ];
                $chapters[$key]['me'] = $me;
            }
        }

        return $chapters;
    }

    /**
     * @param array $chapter
     * @return array
     */
    protected function handleChapter($chapter)
    {

        $attrs = json_decode($chapter['attrs'], true);

        $me = $chapter['me'] ?? [];

        $clickable = $chapter['published'];

        if ($attrs['model'] == CourseModel::MODEL_VOD) {
            unset($attrs['file_id'], $attrs['file_status']);
        }

        /**
         * 直播前后半小时缓冲区间可用
         */
        if ($attrs['model'] == CourseModel::MODEL_LIVE) {
            $caseA = $attrs['start_time'] - time() < 1800;
            $caseB = time() - $attrs['end_time'] < 1800;
            if ($caseA && $caseB) {
                $clickable = 1;
            }
        }


        $result = [
            'id' => $chapter['id'],
            'title' => $chapter['title'],
            'summary' => $chapter['summary'],
            'free' => $chapter['free'],
            'clickable' => $clickable,
            'attrs' => $attrs,
            'me' => $me,
        ];

        return $result;
    }

}
