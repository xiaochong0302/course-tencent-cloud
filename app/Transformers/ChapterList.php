<?php

namespace App\Transformers;

class ChapterList extends Transformer
{

    public function handleProcess($chapters, $studyHistory)
    {
        $status = [];

        if ($studyHistory) {
            foreach ($studyHistory as $value) {
                $status[$value['chapter_id']] = $value['finished'];
            }
        }

        foreach ($chapters as $key => $chapter) {
            $chapters[$key]['finished'] = isset($status[$chapter['id']]) ? $status[$chapter['id']] : 0;
        }

        return $chapters;
    }

    public function handleTree($chapters)
    {
        $list = [];

        foreach ($chapters as $chapter) {
            if ($chapter['parent_id'] == 0) {
                $list[$chapter['id']] = $chapter;
                $list[$chapter['id']]['child'] = [];
            } else {
                $list[$chapter['parent_id']]['child'][] = $chapter;
            }
        }

        usort($list, function($a, $b) {
            return $a['priority'] > $b['priority'];
        });

        foreach ($list as $key => $value) {
            usort($list[$key]['child'], function($a, $b) {
                return $a['priority'] > $b['priority'];
            });
        }

        return $list;
    }

}
