<?php

namespace App\Http\Home\Services;

class ShareUrl extends Service
{

    /**
     * WEB站点URL
     *
     * @var string
     */
    protected $webBaseUrl;

    /**
     * H5站点URL
     *
     * @var string
     */
    protected $h5BaseUrl;

    public function __construct()
    {
        $this->webBaseUrl = $this->getWebBaseUrl();
        $this->h5BaseUrl = $this->getH5BaseUrl();
    }

    public function handle($id, $type, $referer = 0)
    {
        if ($type == 'article') {
            $result = $this->getArticleUrl($id, $referer);
        } elseif ($type == 'course') {
            $result = $this->getCourseUrl($id, $referer);
        } elseif ($type == 'chapter') {
            $result = $this->getChapterUrl($id, $referer);
        } elseif ($type == 'package') {
            $result = $this->getPackageUrl($id, $referer);
        } elseif ($type == 'vip') {
            $result = $this->getVipUrl($id, $referer);
        } elseif ($type == 'user') {
            $result = $this->getUserUrl($id, $referer);
        } else {
            $result = $this->getHomeUrl($referer);
        }

        return $this->h5Enabled() ? $result['h5'] : $result['web'];
    }

    public function getHomeUrl($referer = 0)
    {
        $webUrl = sprintf('%s?referer=%s', $this->webBaseUrl, $referer);

        $h5Url = sprintf('%s?referer=%s', $this->h5BaseUrl, $referer);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getArticleUrl($id, $referer = 0)
    {
        $route = $this->url->get(
            ['for' => 'home.article.show', 'id' => $id],
            ['referer' => $referer]
        );

        $webUrl = $this->webBaseUrl . $route;

        $h5Url = sprintf('%s/article/info?id=%s&referer=%s', $this->h5BaseUrl, $id, $referer);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getCourseUrl($id, $referer = 0)
    {
        $route = $this->url->get(
            ['for' => 'home.course.show', 'id' => $id],
            ['referer' => $referer]
        );

        $webUrl = $this->webBaseUrl . $route;

        $h5Url = sprintf('%s/course/info?id=%s&referer=%s', $this->h5BaseUrl, $id, $referer);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getChapterUrl($id, $referer = 0)
    {
        $route = $this->url->get(
            ['for' => 'home.chapter.show', 'id' => $id],
            ['referer' => $referer]
        );

        $webUrl = $this->webBaseUrl . $route;

        $h5Url = sprintf('%s/chapter/info?id=%s&referer=%s', $this->h5BaseUrl, $id, $referer);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getPackageUrl($id, $referer = 0)
    {
        $route = $this->url->get(
            ['for' => 'home.package.show', 'id' => $id],
            ['referer' => $referer]
        );

        $webUrl = $this->webBaseUrl . $route;

        $h5Url = sprintf('%s/package/info?id=%s&referer=%s', $this->h5BaseUrl, $id, $referer);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getUserUrl($id, $referer = 0)
    {
        $route = $this->url->get(
            ['for' => 'home.user.show', 'id' => $id],
            ['referer' => $referer]
        );

        $webUrl = $this->webBaseUrl . $route;

        $h5Url = sprintf('%s/user/info?id=%s&referer=%s', $this->h5BaseUrl, $id, $referer);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getVipUrl($id, $referer = 0)
    {
        $route = $this->url->get(
            ['for' => 'home.vip.index'],
            ['id' => $id, 'referer' => $referer]
        );

        $webUrl = $this->webBaseUrl . $route;

        $h5Url = sprintf('%s/vip/index?id=%s&referer=%s', $this->h5BaseUrl, $id, $referer);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    protected function h5Enabled()
    {
        $file = public_path('h5/index.html');

        return file_exists($file);
    }

    protected function getWebBaseUrl()
    {
        return kg_site_url();
    }

    protected function getH5BaseUrl()
    {
        return sprintf('%s/h5/#/pages', kg_site_url());
    }

}
