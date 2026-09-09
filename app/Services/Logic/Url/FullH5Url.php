<?php
/**
 * @copyright Copyright (c) 2022 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Url;

use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Services\Service as AppService;

class FullH5Url extends AppService
{

    /**
     * 跳转来源
     *
     * @var string
     */
    protected string $source = 'pc';

    public function getHomeUrl(): string
    {
        return $this->getFullUrl('/index/index');
    }

    public function getAccountRegisterUrl(): string
    {
        return $this->getFullUrl('/account/register');
    }

    public function getAccountLoginUrl(): string
    {
        return $this->getFullUrl('/account/login');
    }

    public function getAccountForgetUrl(): string
    {
        return $this->getFullUrl('/account/forget');
    }

    public function getCourseListUrl(): string
    {
        return $this->getFullUrl('/course/list');
    }

    public function getTeacherListUrl(): string
    {
        return $this->getFullUrl('/teacher/list');
    }

    public function getPageInfoUrl(string|int $id): string
    {
        return $this->getFullUrl('/page/info', ['id' => $id]);
    }

    public function getCourseInfoUrl(int $id): string
    {
        return $this->getFullUrl('/course/info', ['id' => $id]);
    }

    public function getChapterInfoUrl(int $id): string
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($id);

        if ($chapter->model == CourseModel::MODEL_VOD) {
            return $this->getFullUrl('/chapter/vod', ['id' => $id]);
        } elseif ($chapter->model == CourseModel::MODEL_READ) {
            return $this->getFullUrl('/chapter/read', ['id' => $id]);
        } else {
            return $this->getHomeUrl();
        }
    }

    public function getUserIndexUrl(int $id): string
    {
        return $this->getFullUrl('/user/index', ['id' => $id]);
    }

    public function getTeacherIndexUrl(int $id): string
    {
        return $this->getFullUrl('/teacher/index', ['id' => $id]);
    }

    public function getVipIndexUrl(): string
    {
        return $this->getFullPromotionUrl('/vip/index');
    }

    protected function getFullUrl(mixed $path, array $params = []): string
    {
        $extra = ['source' => $this->source];

        $data = array_merge($params, $extra);

        $query = http_build_query($data);

        return sprintf('%s%s?%s', $this->getBaseUrl(), $path, $query);
    }

    protected function getFullPromotionUrl(mixed $path, array $params = []): string
    {
        $extra = ['source' => $this->source];

        $data = array_merge($params, $extra);

        $query = http_build_query($data);

        return sprintf('%s%s?%s', $this->getPromotionBaseUrl(), $path, $query);
    }

    protected function getFullMeUrl(mixed $path, array $params = []): string
    {
        $extra = ['source' => $this->source];

        $data = array_merge($params, $extra);

        $query = http_build_query($data);

        return sprintf('%s%s?%s', $this->getMeBaseUrl(), $path, $query);
    }

    protected function getBaseUrl(): string
    {
        return sprintf('%s/h5/#/pages', kg_site_url());
    }

    protected function getPromotionBaseUrl()
    {
        return sprintf('%s/h5/#/pages-promotion', kg_site_url());
    }

    protected function getMeBaseUrl()
    {
        return sprintf('%s/h5/#/pages-me', kg_site_url());
    }

}
