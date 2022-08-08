<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

class FullWebUrl extends Service
{

    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = $this->getBaseUrl();
    }

    public function getHomeUrl()
    {
        return $this->baseUrl;
    }

    public function getArticleShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.article.show', 'id' => $id]);

        return $this->baseUrl . $route;
    }

    public function getQuestionShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.question.show', 'id' => $id]);

        return $this->baseUrl . $route;
    }

    public function getCourseShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.course.show', 'id' => $id]);

        return $this->baseUrl . $route;
    }

    public function getChapterShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.chapter.show', 'id' => $id]);

        return $this->baseUrl . $route;
    }

    public function getUserShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.user.show', 'id' => $id]);

        return $this->baseUrl . $route;
    }

    public function getTeacherShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.teacher.show', 'id' => $id]);

        return $this->baseUrl . $route;
    }

    public function getPointGiftShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.point_gift.show', 'id' => $id]);

        return $this->baseUrl . $route;
    }

    protected function getBaseUrl()
    {
        return kg_site_url();
    }

}