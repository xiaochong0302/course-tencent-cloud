<?php
/**
 * @copyright Copyright (c) 2022 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.com
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
    protected string $baseUrl;

    /**
     * 跳转来源
     *
     * @var string
     */
    protected string $source = 'pc';

    public function __construct()
    {
        $this->baseUrl = $this->getBaseUrl();
    }

    public function getHomeUrl(): string
    {
        return $this->baseUrl;
    }

    public function getVipUrl(): string
    {
        $route = $this->url->get(['for' => 'home.vip.index']);

        return $this->getFullUrl($route);
    }

    public function getPageShowUrl(int $id): string
    {
        $route = $this->url->get(['for' => 'home.page.show', 'id' => $id]);

        return $this->getFullUrl($route);
    }

    public function getCourseShowUrl(int $id): string
    {
        $route = $this->url->get(['for' => 'home.course.show', 'id' => $id]);

        return $this->getFullUrl($route);
    }

    public function getChapterShowUrl(int $id): string
    {
        $route = $this->url->get(['for' => 'home.chapter.show', 'id' => $id]);

        return $this->getFullUrl($route);
    }

    public function getUserShowUrl(int $id): string
    {
        $route = $this->url->get(['for' => 'home.user.show', 'id' => $id]);

        return $this->getFullUrl($route);
    }

    public function getTeacherShowUrl(int $id): string
    {
        $route = $this->url->get(['for' => 'home.teacher.show', 'id' => $id]);

        return $this->getFullUrl($route);
    }

    protected function getFullUrl(mixed $path, array $params = []): string
    {
        $extra = ['source' => $this->source];

        $data = array_merge($params, $extra);

        $query = http_build_query($data);

        return sprintf('%s%s?%s', $this->baseUrl, $path, $query);
    }

    protected function getBaseUrl(): string
    {
        return kg_site_url();
    }

}
