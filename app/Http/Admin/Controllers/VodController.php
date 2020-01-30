<?php

namespace App\Http\Admin\Controllers;

use App\Models\Learning as LearningModel;
use App\Services\LearningSyncer as LearningSyncerService;
use App\Services\Vod as VodService;
use Phalcon\Mvc\View;

/**
 * @RoutePrefix("/admin/vod")
 */
class VodController extends Controller
{

    /**
     * @Post("/upload/signature", name="admin.vod.upload.signature")
     */
    public function uploadSignatureAction()
    {
        $service = new VodService();

        $signature = $service->getUploadSignature();

        return $this->ajaxSuccess(['signature' => $signature]);
    }

    /**
     * @Get("/player", name="admin.vod.player")
     */
    public function playerAction()
    {
        $courseId = $this->request->getQuery('course_id');
        $chapterId = $this->request->getQuery('chapter_id');
        $playUrl = $this->request->getQuery('play_url');

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $this->view->pick('public/vod_player');

        $this->view->setVar('course_id', $courseId);
        $this->view->setVar('chapter_id', $chapterId);
        $this->view->setVar('play_url', urldecode($playUrl));
    }

    /**
     * @Get("/learning", name="admin.vod.learning")
     */
    public function learningAction()
    {
        $query = $this->request->getQuery();

        $learning = new LearningModel();

        $learning->user_id = $this->authUser->id;
        $learning->request_id = $query['request_id'];
        $learning->course_id = $query['course_id'];
        $learning->chapter_id = $query['chapter_id'];
        $learning->position = $query['position'];

        $syncerService = new LearningSyncerService();

        $syncerService->save($learning, $query['timeout']);

        return $this->ajaxSuccess();
    }

}
