<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Caches\AppInfo as AppInfoCache;
use App\Caches\SiteGlobalStat as SiteGlobalStatCache;
use App\Caches\SiteTodayStat as SiteTodayStatCache;
use App\Library\AppInfo;
use App\Library\Utils\ServerInfo;
use App\Repos\Stat as StatRepo;
use GuzzleHttp\Client;

class Index extends Service
{

    public function getTopMenus()
    {
        $authMenu = new AuthMenu();

        return $authMenu->getTopMenus();
    }

    public function getLeftMenus()
    {
        $authMenu = new AuthMenu();

        return $authMenu->getLeftMenus();
    }

    public function getAppInfo()
    {
        $cache = new AppInfoCache();

        $content = $cache->get();

        $appInfo = new AppInfo();

        if (empty($content) || $appInfo->get('version') != $content['version']) {
            $cache->rebuild();
        }

        return $appInfo;
    }

    public function getSiteInfo()
    {
        return $this->getSettings('site');
    }

    public function getServerInfo()
    {
        return [
            'cpu' => ServerInfo::cpu(),
            'memory' => ServerInfo::memory(),
            'disk' => ServerInfo::disk(),
        ];
    }

    public function getGlobalStat()
    {
        $cache = new SiteGlobalStatCache();

        return $cache->get();
    }

    public function getTodayStat()
    {
        $cache = new SiteTodayStatCache();

        return $cache->get();
    }

    public function getModerationStat()
    {
        $statRepo = new StatRepo();

        $reviewCount = $statRepo->countPendingReviews();
        $consultCount = $statRepo->countPendingConsults();
        $articleCount = $statRepo->countPendingArticles();
        $questionCount = $statRepo->countPendingQuestions();
        $answerCount = $statRepo->countPendingAnswers();
        $commentCount = $statRepo->countPendingComments();

        return [
            'review_count' => $reviewCount,
            'consult_count' => $consultCount,
            'article_count' => $articleCount,
            'question_count' => $questionCount,
            'answer_count' => $answerCount,
            'comment_count' => $commentCount,
        ];
    }

    public function getReportStat()
    {
        $statRepo = new StatRepo();

        $articleCount = $statRepo->countReportedArticles();
        $questionCount = $statRepo->countReportedQuestions();
        $answerCount = $statRepo->countReportedAnswers();
        $commentCount = $statRepo->countReportedComments();

        return [
            'article_count' => $articleCount,
            'question_count' => $questionCount,
            'answer_count' => $answerCount,
            'comment_count' => $commentCount,
        ];
    }

    public function getReleases()
    {
        $url = 'https://www.koogua.com/api/releases';

        $client = new Client();

        $response = $client->get($url);

        $content = json_decode($response->getBody(), true);

        return $content['releases'] ?? [];
    }

}
