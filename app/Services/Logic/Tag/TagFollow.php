<?php

namespace App\Services\Logic\Tag;

use App\Models\Tag as TagModel;
use App\Models\TagFollow as TagFollowModel;
use App\Repos\TagFollow as TagFollowRepo;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\TagTrait;

class TagFollow extends LogicService
{

    use TagTrait;

    public function handle($id)
    {
        $tag = $this->checkTag($id);

        $user = $this->getLoginUser();

        $followRepo = new TagFollowRepo();

        $tagFollow = $followRepo->findTagFollow($tag->id, $user->id);

        if (!$tagFollow) {

            $action = 'do';

            $tagFollow = new TagFollowModel();

            $tagFollow->tag_id = $tag->id;
            $tagFollow->user_id = $user->id;

            $tagFollow->create();

            $this->incrTagFollowCount($tag);

        } else {

            $action = 'undo';

            $tagFollow->delete();

            $this->decrTagFollowCount($tag);
        }

        return [
            'action' => $action,
            'count' => $tag->follow_count,
        ];
    }

    protected function incrTagFollowCount(TagModel $tag)
    {
        $tag->follow_count += 1;

        $tag->update();
    }

    protected function decrTagFollowCount(TagModel $tag)
    {
        if ($tag->follow_count > 0) {
            $tag->follow_count -= 1;
            $tag->update();
        }
    }

}
