<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Controllers;

use App\Models\ChapterLive as LiveModel;
use App\Models\Course as CourseModel;
use App\Services\Logic\Chapter\ChapterInfo as ChapterInfoService;
use App\Services\Logic\Chapter\ChapterLike as ChapterLikeService;
use App\Services\Logic\Chapter\Learning as ChapterLearningService;
use App\Services\Logic\Chapter\ResourceList as ChapterResourceListService;
use App\Services\Logic\Course\BasicInfo as CourseInfoService;
use App\Services\Logic\Course\ChapterList as CourseChapterListService;

/**
 * @RoutePrefix("/chapter")
 */
class ChapterController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/resources", name="home.chapter.resources")
     */
    public function resourcesAction($id)
    {
        $service = new ChapterResourceListService();

        $items = $service->handle($id);

        $this->view->setVar('items', $items);
    }

    /**
     * @Get("/{id:[0-9]+}", name="home.chapter.show")
     */
    public function showAction($id)
    {
        $service = new ChapterInfoService();

        $chapter = $service->handle($id);

        if ($chapter['deleted'] == 1) {
            return $this->notFound();
        }

        $service = new CourseInfoService();

        $course = $service->handle($chapter['course']['id']);

        $owned = $chapter['me']['owned'] ?? false;

        if (!$owned) {
            $this->response->redirect([
                'for' => 'home.course.show',
                'id' => $course['id'],
            ]);
        }

        $service = new CourseChapterListService();

        $catalog = $service->handle($course['id']);

        $this->seo->prependTitle(['章节', $chapter['title'], $course['title']]);

        if (!empty($chapter['summary'])) {
            $this->seo->setDescription($chapter['summary']);
        }

        if ($chapter['model'] == CourseModel::MODEL_VOD) {
            $this->view->pick('chapter/vod');
        } elseif ($chapter['model'] == CourseModel::MODEL_READ) {
            $this->view->pick('chapter/read');
        } elseif ($chapter['model'] == CourseModel::MODEL_LIVE) {
            if ($chapter['status'] == LiveModel::STATUS_ACTIVE) {
                $this->view->pick('chapter/live/active');
            } elseif ($chapter['status'] == LiveModel::STATUS_INACTIVE) {
                $this->view->pick('chapter/live/inactive');
            } elseif ($chapter['status'] == LiveModel::STATUS_FORBID) {
                $this->view->pick('chapter/live/forbid');
            }
        }

        $this->view->setVar('course', $course);
        $this->view->setVar('chapter', $chapter);
        $this->view->setVar('catalog', $catalog);
    }

    /**
     * @Post("/{id:[0-9]+}/like", name="home.chapter.like")
     */
    public function likeAction($id)
    {
        $service = new ChapterLikeService();

        $data = $service->handle($id);

        $msg = $data['action'] == 'do' ? '点赞成功' : '取消点赞成功';

        return $this->jsonSuccess(['data' => $data, 'msg' => $msg]);
    }

    /**
     * @Post("/{id:[0-9]+}/learning", name="home.chapter.learning")
     */
    public function learningAction($id)
    {
        $service = new ChapterLearningService();

        $service->handle($id);

        return $this->jsonSuccess();
    }

}
