<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Traits\Client as ClientTrait;

class FullH5Url extends Service
{

    protected $baseUrl;

    use ClientTrait;

    public function __construct()
    {
        $this->baseUrl = $this->getBaseUrl();
    }

    public function getHomeUrl()
    {
        return sprintf('%s/index/index', $this->baseUrl);
    }

    public function getAccountRegisterUrl()
    {
        return sprintf('%s/account/register', $this->baseUrl);
    }

    public function getAccountLoginUrl()
    {
        return sprintf('%s/account/login', $this->baseUrl);
    }

    public function getAccountForgetUrl()
    {
        return sprintf('%s/account/forget', $this->baseUrl);
    }

    public function getVipIndexUrl()
    {
        return sprintf('%s/vip/index', $this->baseUrl);
    }

    public function getHelpIndexUrl()
    {
        return sprintf('%s/help/index', $this->baseUrl);
    }

    public function getCourseListUrl()
    {
        return sprintf('%s/course/list', $this->baseUrl);
    }

    public function getArticleListUrl()
    {
        return sprintf('%s/article/list', $this->baseUrl);
    }

    public function getQuestionListUrl()
    {
        return sprintf('%s/question/list', $this->baseUrl);
    }

    public function getLiveListUrl()
    {
        return sprintf('%s/discovery/index', $this->baseUrl);
    }

    public function getTeacherListUrl()
    {
        return sprintf('%s/discovery/index', $this->baseUrl);
    }

    public function getImGroupListUrl()
    {
        return sprintf('%s/discovery/index', $this->baseUrl);
    }

    public function getPointGiftListUrl()
    {
        return sprintf('%s/point/gift/list', $this->baseUrl);
    }

    public function getPageInfoUrl($id)
    {
        return sprintf('%s/page/info?id=%s', $this->baseUrl, $id);
    }

    public function getHelpInfoUrl($id)
    {
        return sprintf('%s/help/info?id=%s', $this->baseUrl, $id);
    }

    public function getArticleInfoUrl($id)
    {
        return sprintf('%s/article/info?id=%s', $this->baseUrl, $id);
    }

    public function getQuestionInfoUrl($id)
    {
        return sprintf('%s/question/info?id=%s', $this->baseUrl, $id);
    }

    public function getAnswerInfoUrl($id)
    {
        return sprintf('%s/answer/info?id=%s', $this->baseUrl, $id);
    }

    public function getCourseInfoUrl($id)
    {
        return sprintf('%s/course/info?id=%s', $this->baseUrl, $id);
    }

    public function getChapterInfoUrl($id)
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($id);

        if ($chapter->model == CourseModel::MODEL_VOD) {
            return sprintf('%s/chapter/vod?id=%s', $this->baseUrl, $id);
        } elseif ($chapter->model == CourseModel::MODEL_LIVE) {
            return sprintf('%s/chapter/live?id=%s', $this->baseUrl, $id);
        } elseif ($chapter->model == CourseModel::MODEL_READ) {
            return sprintf('%s/chapter/read?id=%s', $this->baseUrl, $id);
        } else {
            return $this->getHomeUrl();
        }
    }

    public function getUserIndexUrl($id)
    {
        return sprintf('%s/user/index?id=%s', $this->baseUrl, $id);
    }

    public function getTeacherIndexUrl($id)
    {
        return sprintf('%s/teacher/index?id=%s', $this->baseUrl, $id);
    }

    public function getImGroupIndexUrl($id)
    {
        return sprintf('%s/im/group/index?id=%s', $this->baseUrl, $id);
    }

    public function getPointGiftInfoUrl($id)
    {
        return sprintf('%s/point/gift/info?id=%s', $this->baseUrl, $id);
    }

    protected function getBaseUrl()
    {
        return sprintf('%s/h5/#/pages', kg_site_url());
    }

}
