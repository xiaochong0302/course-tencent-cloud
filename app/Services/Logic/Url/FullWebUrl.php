<?php
/**
 *  @copyright Copyright (c) 2022 深圳市酷瓜软件有限公司
 *  @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *  @link https://www.koogua.com
 */

namespace App\Services\Logic\Url;

use App\Services\Service;

class FullWebUrl extends Service
{

    /**
     * 基准地址
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * 跳转来源
     *
     * @var string
     */
    protected $source = 'pc';

    public function __construct()
    {
        $this->baseUrl = $this->getBaseUrl();
    }

    public function getHomeUrl()
    {
        return $this->baseUrl;
    }

    public function getVipUrl()
    {
        $route = $this->url->get(['for' => 'home.vip.index']);

        return $this->getFullUrl($route);
    }

    public function getHelpShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.help.show', 'id' => $id]);

        return $this->getFullUrl($route);
    }

    public function getPageShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.page.show', 'id' => $id]);

        return $this->getFullUrl($route);
    }

    public function getArticleShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.article.show', 'id' => $id]);

        return $this->getFullUrl($route);
    }

    public function getQuestionShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.question.show', 'id' => $id]);

        return $this->getFullUrl($route);
    }

    public function getTopicShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.topic.show', 'id' => $id]);

        return $this->getFullUrl($route);
    }

    public function getPackageShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.package.show', 'id' => $id]);

        return $this->getFullUrl($route);
    }

    public function getCourseShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.course.show', 'id' => $id]);

        return $this->getFullUrl($route);
    }

    public function getChapterShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.chapter.show', 'id' => $id]);

        return $this->getFullUrl($route);
    }

    public function getUserShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.user.show', 'id' => $id]);

        return $this->getFullUrl($route);
    }

    public function getTeacherShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.teacher.show', 'id' => $id]);

        return $this->getFullUrl($route);
    }

    public function getPointGiftShowUrl($id)
    {
        $route = $this->url->get(['for' => 'home.point_gift.show', 'id' => $id]);

        return $this->getFullUrl($route);
    }

    protected function getFullUrl($path, $params = [])
    {
        $extra = ['source' => $this->source];

        $data = array_merge($params, $extra);

        $query = http_build_query($data);

        return sprintf('%s%s?%s', $this->baseUrl, $path, $query);
    }

    protected function getBaseUrl()
    {
        return kg_site_url();
    }

}