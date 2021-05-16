<?php

namespace App\Http\Admin\Services;

use App\Caches\SiteGlobalStat;
use App\Caches\SiteTodayStat;
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
        return new AppInfo();
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
        $cache = new SiteGlobalStat();

        return $cache->get();
    }

    public function getTodayStat()
    {
        $cache = new SiteTodayStat();

        return $cache->get();
    }

    public function getModerationStat()
    {
        $statRepo = new StatRepo();

        $articleCount = $statRepo->countPendingArticles();
        $questionCount = $statRepo->countPendingQuestions();
        $answerCount = $statRepo->countPendingAnswers();
        $commentCount = $statRepo->countPendingComments();

        return [
            'article_count' => $articleCount,
            'question_count' => $questionCount,
            'answer_count' => $answerCount,
            'comment_count' => $commentCount,
        ];
    }

    public function getReleases()
    {
        $url = 'https://koogua.com/api-releases.json';

        $client = new Client();

        $response = $client->get($url);

        $content = json_decode($response->getBody(), true);

        return $content['releases'] ?? [];
    }

}
