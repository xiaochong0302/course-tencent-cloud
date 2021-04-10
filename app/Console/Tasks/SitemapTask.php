<?php

namespace App\Console\Tasks;

use App\Library\Sitemap;
use App\Models\Article as ArticleModel;
use App\Models\Course as CourseModel;
use App\Models\Help as HelpModel;
use App\Models\ImGroup as ImGroupModel;
use App\Models\Page as PageModel;
use App\Models\Topic as TopicModel;
use App\Models\User as UserModel;
use App\Services\Service as AppService;
use Phalcon\Mvc\Model\Resultset;

class SitemapTask extends Task
{

    /**
     * @var string
     */
    protected $siteUrl;

    /**
     * @var Sitemap
     */
    protected $sitemap;

    public function mainAction()
    {
        $this->siteUrl = $this->getSiteUrl();

        $this->sitemap = new Sitemap();

        $filename = tmp_path('sitemap.xml');

        $this->addIndex();
        $this->addCourses();
        $this->addArticles();
        $this->addTeachers();
        $this->addTopics();
        $this->addImGroups();
        $this->addHelps();
        $this->addPages();
        $this->addOthers();

        $this->sitemap->build($filename);
    }

    protected function getSiteUrl()
    {
        $service = new AppService();

        $settings = $service->getSettings('site');

        return $settings['url'] ?? '';
    }

    protected function addIndex()
    {
        $this->sitemap->addItem($this->siteUrl, 1);
    }

    protected function addCourses()
    {
        /**
         * @var Resultset|CourseModel[] $courses
         */
        $courses = CourseModel::query()->where('published = 1')->execute();

        if ($courses->count() == 0) return;

        foreach ($courses as $course) {
            $loc = sprintf('%s/course/%s', $this->siteUrl, $course->id);
            $this->sitemap->addItem($loc, 0.8);
        }
    }

    protected function addArticles()
    {
        /**
         * @var Resultset|ArticleModel[] $articles
         */
        $articles = ArticleModel::query()->where('published = 1')->execute();

        if ($articles->count() == 0) return;

        foreach ($articles as $article) {
            $loc = sprintf('%s/article/%s', $this->siteUrl, $article->id);
            $this->sitemap->addItem($loc, 0.8);
        }
    }

    protected function addTeachers()
    {
        /**
         * @var Resultset|UserModel[] $teachers
         */
        $teachers = UserModel::query()->where('edu_role = 2')->execute();

        if ($teachers->count() == 0) return;

        foreach ($teachers as $teacher) {
            $loc = sprintf('%s/teacher/%s', $this->siteUrl, $teacher->id);
            $this->sitemap->addItem($loc, 0.6);
        }
    }

    protected function addTopics()
    {
        /**
         * @var Resultset|TopicModel[] $topics
         */
        $topics = TopicModel::query()->where('published = 1')->execute();

        if ($topics->count() == 0) return;

        foreach ($topics as $topic) {
            $loc = sprintf('%s/topic/%s', $this->siteUrl, $topic->id);
            $this->sitemap->addItem($loc, 0.6);
        }
    }

    protected function addImGroups()
    {
        /**
         * @var Resultset|ImGroupModel[] $groups
         */
        $groups = ImGroupModel::query()->where('published = 1')->execute();

        if ($groups->count() == 0) return;

        foreach ($groups as $group) {
            $loc = sprintf('%s/im/group/%s', $this->siteUrl, $group->id);
            $this->sitemap->addItem($loc, 0.6);
        }
    }

    protected function addPages()
    {
        /**
         * @var Resultset|PageModel[] $pages
         */
        $pages = PageModel::query()->where('published = 1')->execute();

        if ($pages->count() == 0) return;

        foreach ($pages as $page) {
            $loc = sprintf('%s/page/%s', $this->siteUrl, $page->id);
            $this->sitemap->addItem($loc, 0.7);
        }
    }

    protected function addHelps()
    {
        /**
         * @var Resultset|HelpModel[] $helps
         */
        $helps = HelpModel::query()->where('published = 1')->execute();

        if ($helps->count() == 0) return;

        foreach ($helps as $help) {
            $loc = sprintf('%s/help/%s', $this->siteUrl, $help->id);
            $this->sitemap->addItem($loc, 0.7);
        }
    }

    protected function addOthers()
    {
        $this->sitemap->addItem('/course/list', 0.6);
        $this->sitemap->addItem('/im/group/list', 0.6);
        $this->sitemap->addItem('/teacher/list', 0.6);
        $this->sitemap->addItem('/vip', 0.6);
        $this->sitemap->addItem('/help', 0.6);
        $this->sitemap->addItem('/search', 0.6);
    }

}
