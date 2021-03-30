<?php

namespace App\Services\Logic\Chapter;

use App\Models\Course as CourseModel;
use App\Models\Learning as LearningModel;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Sync\Learning as LearningSyncService;
use App\Validators\Learning as LearningValidator;

class Learning extends LogicService
{

    use ChapterTrait;

    public function handle($id)
    {
        $post = $this->request->getPost();

        $chapter = $this->checkChapter($id);

        $user = $this->getLoginUser();

        $validator = new LearningValidator();

        $data = [
            'course_id' => $chapter->course_id,
            'chapter_id' => $chapter->id,
            'user_id' => $user->id,
            'position' => 0,
        ];

        $data['request_id'] = $validator->checkRequestId($post['request_id']);
        $data['plan_id'] = $validator->checkPlanId($post['plan_id']);

        if ($chapter->model == CourseModel::MODEL_VOD) {
            $data['position'] = $validator->checkPosition($post['position']);
        }

        $intervalTime = $validator->checkIntervalTime($post['interval_time']);

        $learning = new LearningModel($data);

        $sync = new LearningSyncService();

        $sync->addItem($learning, $intervalTime);
    }

}