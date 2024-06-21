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
use App\Services\Logic\Course\BasicInfo as CourseInfoService;
use App\Services\Logic\Course\ChapterList as CourseChapterListService;
use App\Services\Logic\Url\FullH5Url as FullH5UrlService;

/**
 * @RoutePrefix("/chapter")
 */
class ChapterController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}", name="home.chapter.show")
     */
    public function showAction($id)
    {
        $service = new FullH5UrlService();

        if ($service->isMobileBrowser() && $service->h5Enabled()) {
            $location = $service->getChapterInfoUrl($id);
            return $this->response->redirect($location);
        }

        if ($this->authUser->id == 0) {
            return $this->response->redirect(['for' => 'home.account.login']);
        }

        $service = new ChapterInfoService();

        $chapter = $service->handle($id);

        if ($chapter['deleted'] == 1) {
            $this->notFound();
        }

        if ($chapter['published'] == 0) {
            $this->notFound();
        }

        if ($chapter['me']['owned'] == 0) {
            $this->forbidden();
        }

        $service = new CourseInfoService();

        $course = $service->handle($chapter['course']['id']);

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
