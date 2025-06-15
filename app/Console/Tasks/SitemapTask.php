<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Library\Sitemap;
use App\Models\Article as ArticleModel;
use App\Models\Course as CourseModel;
use App\Models\Help as HelpModel;
use App\Models\Page as PageModel;
use App\Models\Question as QuestionModel;
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

        $filename = public_path('sitemap.xml');

        echo '------ start sitemap task ------' . PHP_EOL;

        $this->addIndex();
        $this->addCourses();
        $this->addArticles();
        $this->addQuestions();
        $this->addTeachers();
        $this->addTopics();
        $this->addHelps();
        $this->addPages();
        $this->addOthers();

        $this->sitemap->build($filename);

        echo '------ end sitemap task ------' . PHP_EOL;
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
        $courses = CourseModel::query()
            ->where('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('id DESC')
            ->limit(500)
            ->execute();

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
        $articles = ArticleModel::query()
            ->where('published = :published:', ['published' => ArticleModel::PUBLISH_APPROVED])
            ->andWhere('deleted = 0')
            ->orderBy('id DESC')
            ->limit(500)
            ->execute();

        if ($articles->count() == 0) return;

        foreach ($articles as $article) {
            $loc = sprintf('%s/article/%s', $this->siteUrl, $article->id);
            $this->sitemap->addItem($loc, 0.8);
        }
    }

    protected function addQuestions()
    {
        /**
         * @var Resultset|QuestionModel[] $questions
         */
        $questions = QuestionModel::query()
            ->where('published = :published:', ['published' => QuestionModel::PUBLISH_APPROVED])
            ->andWhere('deleted = 0')
            ->orderBy('id DESC')
            ->limit(500)
            ->execute();

        if ($questions->count() == 0) return;

        foreach ($questions as $question) {
            $loc = sprintf('%s/question/%s', $this->siteUrl, $question->id);
            $this->sitemap->addItem($loc, 0.8);
        }
    }

    protected function addTeachers()
    {
        /**
         * @var Resultset|UserModel[] $teachers
         */
        $teachers = UserModel::query()
            ->where('edu_role = :edu_role:', ['edu_role' => UserModel::EDU_ROLE_TEACHER])
            ->andWhere('deleted = 0')
            ->execute();

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
        $topics = TopicModel::query()
            ->where('published = 1')
            ->andWhere('deleted = 0')
            ->execute();

        if ($topics->count() == 0) return;

        foreach ($topics as $topic) {
            $loc = sprintf('%s/topic/%s', $this->siteUrl, $topic->id);
            $this->sitemap->addItem($loc, 0.6);
        }
    }

    protected function addPages()
    {
        /**
         * @var Resultset|PageModel[] $pages
         */
        $pages = PageModel::query()
            ->where('published = 1')
            ->andWhere('deleted = 0')
            ->execute();

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
        $helps = HelpModel::query()
            ->where('published = 1')
            ->andWhere('deleted = 0')
            ->execute();

        if ($helps->count() == 0) return;

        foreach ($helps as $help) {
            $loc = sprintf('%s/help/%s', $this->siteUrl, $help->id);
            $this->sitemap->addItem($loc, 0.7);
        }
    }

    protected function addOthers()
    {
        $this->sitemap->addItem("{$this->siteUrl}/course/list", 0.6);
        $this->sitemap->addItem("{$this->siteUrl}/teacher/list", 0.6);
        $this->sitemap->addItem("{$this->siteUrl}/vip", 0.6);
        $this->sitemap->addItem("{$this->siteUrl}/help", 0.6);
    }

}
