<?php

namespace App\Services\Frontend\Danmu;

use App\Models\Danmu as DanmuModel;
use App\Models\User as UserModel;
use App\Services\Frontend\ChapterTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\Danmu as DanmuValidator;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class DanmuCreate extends FrontendService
{

    use ChapterTrait;

    public function handle()
    {
        $post = $this->request->getPost();

        $user = $this->getLoginUser();

        $chapter = $this->checkChapter($post['chapter_id']);

        $validator = new UserDailyLimitValidator();

        $validator->checkDanmuLimit($user);

        $validator = new DanmuValidator();

        $danmu = new DanmuModel();

        $data = [];

        $data['text'] = $validator->checkText($post['text']);
        $data['time'] = $validator->checkTime($post['time']);

        $data['course_id'] = $chapter->course_id;
        $data['chapter_id'] = $chapter->id;
        $data['user_id'] = $user->id;
        $data['position'] = DanmuModel::POSITION_MOVE;
        $data['color'] = DanmuModel::COLOR_WHITE;
        $data['size'] = DanmuModel::SIZE_SMALL;
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
