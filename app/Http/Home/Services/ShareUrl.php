<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Home\Services;

use App\Traits\Client as ClientTrait;

class ShareUrl extends Service
{

    /**
     * WEB站点URL
     *
     * @var string
     */
    protected $fullWebUrl;

    /**
     * H5站点URL
     *
     * @var string
     */
    protected $fullH5Url;

    use ClientTrait;

    public function __construct()
    {
        $this->fullWebUrl = new FullWebUrl();
        $this->fullH5Url = new FullH5Url();
    }

    public function handle($id, $type)
    {
        if ($type == 'article') {
            $result = $this->getArticleUrl($id);
        } elseif ($type == 'question') {
            $result = $this->getQuestionUrl($id);
        } elseif ($type == 'course') {
            $result = $this->getCourseUrl($id);
        } elseif ($type == 'chapter') {
            $result = $this->getChapterUrl($id);
        } elseif ($type == 'user') {
            $result = $this->getUserUrl($id);
        } elseif ($type == 'teacher') {
            $result = $this->getTeacherUrl($id);
        } else {
            $result = $this->getHomeUrl();
        }

        return $this->h5Enabled() ? $result['h5'] : $result['web'];
    }

    public function getHomeUrl()
    {
        $webUrl = $this->fullWebUrl->getHomeUrl();

        $h5Url = $this->fullH5Url->getHomeUrl();

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getArticleUrl($id = 0)
    {
        $webUrl = $this->fullWebUrl->getArticleShowUrl($id);

        $h5Url = $this->fullH5Url->getArticleInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getQuestionUrl($id = 0)
    {
        $webUrl = $this->fullWebUrl->getQuestionShowUrl($id);

        $h5Url = $this->fullH5Url->getQuestionInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getCourseUrl($id = 0)
    {
        $webUrl = $this->fullWebUrl->getCourseShowUrl($id);

        $h5Url = $this->fullH5Url->getCourseInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getChapterUrl($id = 0)
    {
        $webUrl = $this->fullWebUrl->getChapterShowUrl($id);

        $h5Url = $this->fullH5Url->getChapterInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getUserUrl($id = 0)
    {
        $webUrl = $this->fullWebUrl->getUserShowUrl($id);

        $h5Url = $this->fullH5Url->getUserIndexUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getTeacherUrl($id = 0)
    {
        $webUrl = $this->fullWebUrl->getTeacherShowUrl($id);

        $h5Url = $this->fullH5Url->getTeacherIndexUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

}
