<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Caches\CategoryAllList as CategoryAllListCache;
use App\Caches\CategoryList as CategoryListCache;
use App\Caches\CategoryTreeList as CategoryTreeListCache;
use App\Models\Account as AccountModel;
use App\Models\Category as CategoryModel;
use App\Repos\User as UserRepo;
use App\Services\Utils\IndexPageCache as IndexPageCacheUtil;

class CleanDemoDataTask extends Task
{

    public function mainAction(): void
    {
        if ($this->isDemoEnv()) {

            $this->truncateTables();
            $this->createRootUser();
            $this->cleanSearchIndex();
            $this->cleanContentCache();
            $this->deleteUserCountCache();

        } else {

            echo '------ access denied ------' . PHP_EOL;
        }
    }

    protected function truncateTables(): void
    {
        echo '------ start truncate tables ------' . PHP_EOL;

        $excludeTables = [
            'kg_migration', 'kg_migration_phalcon', 'kg_nav',
            'kg_page', 'kg_role', 'kg_setting', 'kg_vip',
        ];

        $tables = $this->db->listTables();

        foreach ($tables as $table) {
            if (!in_array($table, $excludeTables)) {
                $this->db->execute("TRUNCATE TABLE {$table}");
            }
        }

        echo '------ end truncate tables ------' . PHP_EOL;
    }

    protected function createRootUser(): void
    {
        echo '------ start create root user ------' . PHP_EOL;

        $account = new AccountModel();

        $account->assign([
            'id' => 10000,
            'email' => '10000@163.com',
            'password' => '1a1e4568f1a3740b8853a8a16e29bc87',
            'salt' => 'MbZWxN3L',
            'create_time' => time(),
        ]);

        $account->create();

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

        $user->assign([
            'admin_role' => 1,
            'edu_role' => 2,
        ]);

        $user->update();

        echo '------ end create root user ------' . PHP_EOL;
    }

    protected function cleanContentCache(): void
    {
        $util = new IndexPageCacheUtil();
        $util->rebuild();

        $categoryListCache = new CategoryListCache();
        $categoryAllListCache = new CategoryAllListCache();
        $categoryTreeListCache = new CategoryTreeListCache();

        foreach (CategoryModel::types() as $key => $value) {
            $categoryListCache->rebuild($key);
            $categoryAllListCache->rebuild($key);
            $categoryTreeListCache->rebuild($key);
        }
    }

    protected function cleanSearchIndex(): void
    {
        $courseIndexTask = new CourseIndexTask();
        $courseIndexTask->cleanAction();

        $chapterIndexTask = new ChapterIndexTask();
        $chapterIndexTask->cleanAction();

        $paperIndexTask = new ExamPaperIndexTask();
        $paperIndexTask->cleanAction();

        $articleIndexTask = new ArticleIndexTask();
        $articleIndexTask->cleanAction();

        $questionIndexTask = new QuestionIndexTask();
        $questionIndexTask->cleanAction();
    }

    protected function deleteUserCountCache(): void
    {
        $key = '_APP_USER_COUNT_';

        $cache = $this->getCache();

        $cache->delete($key);
    }

    protected function isDemoEnv(): bool
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById(100015);

        return (bool)$user;
    }

}
