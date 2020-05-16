<?php

namespace App\Services\Frontend\Chapter;

use App\Models\Course as CourseModel;
use App\Models\Learning as LearningModel;
use App\Services\Frontend\ChapterTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Syncer\Learning as LearningSyncerService;
use App\Validators\Learning as LearningValidator;

class Learning extends FrontendService
{

    use ChapterTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapterCache($id);

        $user = $this->getCurrentUser();

        $post = $this->request->getPost();

        if ($user->id == 0) return;

        $validator = new LearningValidator();

        $data = [
            'chapter_id' => $chapter->id,
            'user_id' => $user->id,
        ];

        $data['request_id'] = $validator->checkRequestId($post['request_id']);

        /**
         * @var array $attrs
         */
        $attrs = $chapter->attrs;

        if ($attrs['model'] == CourseModel::MODEL_VOD) {
            $data['position'] = $validator->checkPosition($post['position']);
        }

        $interval = $validator->checkInterval($post['interval']);

        $learning = new LearningModel($data);

        $syncer = new LearningSyncerService();

        $syncer->addItem($learning, $interval);
    }

}