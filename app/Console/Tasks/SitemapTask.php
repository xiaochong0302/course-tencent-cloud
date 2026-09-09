<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Library\Sitemap;
use App\Library\Utils\Lock as LockUtil;
use App\Models\Course as CourseModel;
use App\Models\Page as PageModel;
use App\Models\User as UserModel;
use Phalcon\Mvc\Model\Resultset;

class SitemapTask extends Task
{

    /**
     * @var string
     */
    protected string $siteUrl;

    /**
     * @var Sitemap
     */
    protected Sitemap $sitemap;

    public function mainAction(): void
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        $this->siteUrl = $this->getSiteUrl();

        $this->sitemap = new Sitemap();

        $filename = public_path('sitemap.xml');

        echo '------ start sitemap task ------' . PHP_EOL;

        $this->addIndex();
        $this->addCourses();
        $this->addTeachers();
        $this->addPages();
        $this->addOthers();

        $this->sitemap->build($filename);

        echo '------ end sitemap task ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

    protected function getSiteUrl(): string
    {
        $settings = $this->getSettings('site');

        return $settings['url'] ?? '';
    }

    protected function addIndex(): void
    {
        $this->sitemap->addItem($this->siteUrl, 1);
    }

    protected function addCourses(): void
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

    protected function addTeachers(): void
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

    protected function addPages(): void
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

    protected function addOthers(): void
    {
        $this->sitemap->addItem("{$this->siteUrl}/course/list", 0.6);
        $this->sitemap->addItem("{$this->siteUrl}/teacher/list", 0.6);
        $this->sitemap->addItem("{$this->siteUrl}/vip", 0.6);
    }

}
