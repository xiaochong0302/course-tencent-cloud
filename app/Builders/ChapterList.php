<?php

namespace App\Builders;

use App\Models\Course as CourseModel;

class ChapterList extends Builder
{

    /**
     * @param array $chapters
     * @return array
     */
    public function handleTreeList($chapters)
    {
        $list = [];

        foreach ($chapters as $chapter) {
            if ($chapter['parent_id'] == 0) {
                $key = $chapter['id'];
                $list[$key] = [
                    'id' => $chapter['id'],
                    'title' => $chapter['title'],
                    'summary' => $chapter['summary'],
                    'priority' => $chapter['priority'],
                    'children' => [],
                ];
            } else {
                $key = $chapter['parent_id'];
                $list[$key]['children'][] = $this->handleChapter($chapter);
            }
        }

        usort($list, function ($a, $b) {
            return $a['priority'] > $b['priority'];
        });

        foreach ($list as $key => $value) {
            usort($list[$key]['children'], function ($a, $b) {
                return $a['priority'] > $b['priority'];
            });
        }

        return $list;
    }

    /**
     * @param array $chapter
     * @return array
     */
    protected function handleChapter($chapter)
    {
        $attrs = json_decode($chapter['attrs'], true);

        if ($attrs['model'] == CourseModel::MODEL_VOD) {
            unset($attrs['file_id'], $attrs['file_status']);
        }

        $result = [
            'id' => $chapter['id'],
            'title' => $chapter['title'],
            'summary' => $chapter['summary'],
            'priority' => $chapter['priority'],
            'free' => $chapter['free'],
            'attrs' => $attrs,
        ];

        return $result;
    }

}
