<?php

namespace App\Builders;

class CategoryList extends Builder
{

    public function handleTreeList($categories)
    {
        $list = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] == 0) {
                $key = $category['id'];
                $list[$key] = [
                    'id' => $category['id'],
                    'name' => $category['name'],
                    'priority' => $category['priority'],
                    'children' => [],
                ];
            } else {
                $key = $category['parent_id'];
                $list[$key]['children'][] = [
                    'id' => $category['id'],
                    'name' => $category['name'],
                    'priority' => $category['priority'],
                ];
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

}
