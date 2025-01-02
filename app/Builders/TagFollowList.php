<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Repos\Tag as TagRepo;

class TagFollowList extends Builder
{

    public function handleTags(array $relations)
    {
        $tags = $this->getTags($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['tag'] = $tags[$value['tag_id']] ?? null;
        }

        return $relations;
    }

    public function handleUsers(array $relations)
    {
        $users = $this->getUsers($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['user'] = $users[$value['user_id']] ?? null;
        }

        return $relations;
    }

    public function getTags(array $relations)
    {
        $ids = kg_array_column($relations, 'tag_id');

        $tagRepo = new TagRepo();

        $columns = ['id', 'name', 'alias', 'icon', 'follow_count'];

        $tags = $tagRepo->findByIds($ids, $columns);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($tags->toArray() as $tag) {
            $tag['icon'] = $baseUrl . $tag['icon'];
            $result[$tag['id']] = $tag;
        }

        return $result;
    }

    public function getUsers(array $relations)
    {
        $ids = kg_array_column($relations, 'user_id');

        return $this->getShallowUserByIds($ids);
    }

}
