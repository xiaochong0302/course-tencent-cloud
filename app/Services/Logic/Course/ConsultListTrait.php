<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Course;

use App\Builders\ConsultList as ConsultListBuilder;
use App\Repos\ConsultLike as ConsultLikeRepo;

trait ConsultListTrait
{

    protected function handleConsults($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $consults = $pager->items->toArray();

        $builder = new ConsultListBuilder();

        $users = $builder->getUsers($consults);

        $meMappings = $this->getMeMappings($consults);

        $items = [];

        foreach ($consults as $consult) {

            $owner = $users[$consult['owner_id']] ?? new \stdClass();

            $me = $meMappings[$consult['id']];

            $items[] = [
                'id' => $consult['id'],
                'question' => $consult['question'],
                'answer' => $consult['answer'],
                'like_count' => $consult['like_count'],
                'reply_time' => $consult['reply_time'],
                'create_time' => $consult['create_time'],
                'update_time' => $consult['update_time'],
                'owner' => $owner,
                'me' => $me,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function getMeMappings($consults)
    {
        $user = $this->getCurrentUser(true);

        $likeRepo = new ConsultLikeRepo();

        $likedIds = [];

        if ($user->id > 0) {
            $likes = $likeRepo->findByUserId($user->id)
                ->filter(function ($like) {
                    if ($like->deleted == 0) {
                        return $like;
                    }
                });
            $likedIds = array_column($likes, 'consult_id');
        }

        $result = [];

        foreach ($consults as $consult) {
            $result[$consult['id']] = [
                'logged' => $user->id > 0 ? 1 : 0,
                'liked' => in_array($consult['id'], $likedIds) ? 1 : 0,
                'owned' => $consult['owner_id'] == $user->id ? 1 : 0,
            ];
        }

        return $result;
    }

}
