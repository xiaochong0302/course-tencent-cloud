<?php

namespace App\Builders;

class NavList extends Builder
{

    public function handleTreeList($navs)
    {
        $list = [];

        foreach ($navs as $nav) {
            if ($nav['parent_id'] == 0) {
                $key = $nav['id'];
                $list[$key] = [
                    'id' => $nav['id'],
                    'name' => $nav['name'],
                    'priority' => $nav['priority'],
                    'children' => [],
                ];
            } else {
                $key = $nav['parent_id'];
                $list[$key]['children'][] = [
                    'id' => $nav['id'],
                    'name' => $nav['name'],
                    'priority' => $nav['priority'],
                    'target' => $nav['target'],
                    'url' => $nav['url'],
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
