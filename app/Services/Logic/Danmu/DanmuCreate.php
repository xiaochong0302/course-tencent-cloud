<?php

namespace App\Services\Logic\Danmu;

use App\Models\Danmu as DanmuModel;
use App\Models\User as UserModel;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Service;
use App\Validators\Danmu as DanmuValidator;
use App\Validators\UserLimit as UserLimitValidator;

class DanmuCreate extends Service
{

    use ChapterTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $chapter = $this->checkChapter($post['chapter_id']);

        $validator = new UserLimitValidator();

        $validator->checkDailyDanmuLimit($user);

        $validator = new DanmuValidator();

        $danmu = new DanmuModel();

        $data = [];

        $data['text'] = $validator->checkText($post['text']);
        $data['position'] = $validator->checkPosition($post['position']);
        $data['color'] = $validator->checkColor($post['color']);
        $data['size'] = $validator->checkSize($post['size']);
        $data['time'] = $validator->checkTime($post['time']);

        $data['course_id'] = $chapter->course_id;
        $data['chapter_id'] = $chapter->id;
        $data['owner_id'] = $user->id;

        $data['published'] = 1;

        $danmu->create($data);

        $this->incrUserDailyDanmuCount($user);

        return $danmu;
    }

    protected function incrUserDailyDanmuCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrDanmuCount', $this, $user);
    }

}
