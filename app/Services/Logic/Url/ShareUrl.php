<?php
/**
 * @copyright Copyright (c) 2022 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Url;

use App\Services\Service as AppService;
use App\Traits\Client as ClientTrait;

class ShareUrl extends AppService
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

    /**
     * 目标类型：h5|web
     *
     * @var string
     */
    protected $targetType;

    use ClientTrait;

    public function __construct()
    {
        $this->fullWebUrl = new FullWebUrl();
        $this->fullH5Url = new FullH5Url();
    }

    public function handle($type, $id = 0, $referer = 0)
    {
        if ($type == 'article') {
            $result = $this->getArticleUrl($id);
        } elseif ($type == 'page') {
            $result = $this->getPageUrl($id);
        } elseif ($type == 'help') {
            $result = $this->getHelpUrl($id);
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
        } elseif ($type == 'topic') {
            $result = $this->getTopicUrl($id);
        } elseif ($type == 'package') {
            $result = $this->getPackageUrl($id);
        } elseif ($type == 'vip') {
            $result = $this->getVipUrl();
        } elseif ($type == 'point_gift') {
            $result = $this->getPointGiftUrl($id);
        } else {
            $result = $this->getHomeUrl();
        }

        if ($referer > 0) {
            $result['h5'] = $this->withReferer($result['h5'], $referer);
            $result['web'] = $this->withReferer($result['web'], $referer);
        }

        $gotoH5 = $this->gotoH5Url();

        return $gotoH5 ? $result['h5'] : $result['web'];
    }

    public function getHomeUrl()
    {
        $webUrl = $this->fullWebUrl->getHomeUrl();

        $h5Url = $this->fullH5Url->getHomeUrl();

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getVipUrl()
    {
        $webUrl = $this->fullWebUrl->getVipUrl();

        $h5Url = $this->fullH5Url->getVipIndexUrl();

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getHelpUrl($id)
    {
        $webUrl = $this->fullWebUrl->getHelpShowUrl($id);

        $h5Url = $this->fullH5Url->getHelpInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getPageUrl($id)
    {
        $webUrl = $this->fullWebUrl->getPageShowUrl($id);

        $h5Url = $this->fullH5Url->getPageInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getArticleUrl($id)
    {
        $webUrl = $this->fullWebUrl->getArticleShowUrl($id);

        $h5Url = $this->fullH5Url->getArticleInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getQuestionUrl($id)
    {
        $webUrl = $this->fullWebUrl->getQuestionShowUrl($id);

        $h5Url = $this->fullH5Url->getQuestionInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getTopicUrl($id)
    {
        $webUrl = $this->fullWebUrl->getTopicShowUrl($id);

        $h5Url = $this->fullH5Url->getTopicInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getPackageUrl($id)
    {
        $webUrl = $this->fullWebUrl->getPackageShowUrl($id);

        $h5Url = $this->fullH5Url->getPackageInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getCourseUrl($id)
    {
        $webUrl = $this->fullWebUrl->getCourseShowUrl($id);

        $h5Url = $this->fullH5Url->getCourseInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getChapterUrl($id)
    {
        $webUrl = $this->fullWebUrl->getChapterShowUrl($id);

        $h5Url = $this->fullH5Url->getChapterInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getUserUrl($id)
    {
        $webUrl = $this->fullWebUrl->getUserShowUrl($id);

        $h5Url = $this->fullH5Url->getUserIndexUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getTeacherUrl($id)
    {
        $webUrl = $this->fullWebUrl->getTeacherShowUrl($id);

        $h5Url = $this->fullH5Url->getTeacherIndexUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function getPointGiftUrl($id)
    {

        $webUrl = $this->fullWebUrl->getPointGiftShowUrl($id);

        $h5Url = $this->fullH5Url->getPointGiftInfoUrl($id);

        return ['web' => $webUrl, 'h5' => $h5Url];
    }

    public function setTargetType($targetType)
    {
        $this->targetType = $targetType;
    }

    protected function withReferer($url, $referer)
    {
        $params = ['referer' => $referer];

        if (strpos($url, '?') === false) {
            $url .= '?' . http_build_query($params);
        } else {
            $url .= '&' . http_build_query($params);
        }

        return $url;
    }

    protected function gotoH5Url()
    {
        if (!$this->h5Enabled()) return false;

        if ($this->targetType == 'h5') return true;

        return $this->isMobileBrowser();
    }

}
