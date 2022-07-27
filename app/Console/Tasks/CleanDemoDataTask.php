<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Caches\CategoryList as CategoryListCache;
use App\Caches\CategoryTreeList as CategoryTreeListCache;
use App\Caches\IndexSlideList as IndexSlideListCache;
use App\Models\Account as AccountModel;
use App\Models\Category as CategoryModel;
use App\Repos\User as UserRepo;
use App\Services\Utils\IndexCourseCache as IndexCourseCacheUtil;

class CleanDemoDataTask extends Task
{

    public function mainAction()
    {
        if ($this->isDemoEnv()) {

            $this->truncateTables();
            $this->createRootUser();
            $this->cleanSearchIndex();
            $this->cleanCache();

        } else {

            echo '------ access denied ------' . PHP_EOL;
        }
    }

    protected function truncateTables()
    {
        echo '------ start truncate tables ------' . PHP_EOL;

        $excludeTables = [
            'kg_area', 'kg_migration', 'kg_nav', 'kg_page',
            'kg_reward', 'kg_role', 'kg_setting', 'kg_vip',
        ];

        $tables = $this->db->listTables();

        foreach ($tables as $table) {
            if (!in_array($table, $excludeTables)) {
                $this->db->execute("TRUNCATE TABLE {$table}");
            }
        }

        echo '------ end truncate tables ------' . PHP_EOL;
    }

    protected function createRootUser()
    {
        echo '------ start create root user ------' . PHP_EOL;

        $account = new AccountModel();

        $account->create([
            'id' => 10000,
            'email' => '10000@163.com',
            'password' => '1a1e4568f1a3740b8853a8a16e29bc87',
            'salt' => 'MbZWxN3L',
            'create_time' => time(),
        ]);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($account->id);

        $user->update([
            'admin_role' => 1,
            'edu_role' => 2,
        ]);

        echo '------ end create root user ------' . PHP_EOL;
    }

    protected function cleanCache()
    {
        $util = new IndexCourseCacheUtil();
        $util->rebuild();

        $slideListCache = new IndexSlideListCache();
        $slideListCache->rebuild();

        $categoryListCache = new CategoryListCache();
        $categoryTreeListCache = new CategoryTreeListCache();

        foreach (CategoryModel::types() as $key => $value) {
            $categoryListCache->rebuild($key);
            $categoryTreeListCache->rebuild($key);
        }
    }

    protected function cleanSearchIndex()
    {
        $articleIndexTask = new ArticleIndexTask();
        $articleIndexTask->cleanAction();

        $courseIndexTask = new CourseIndexTask();
        $courseIndexTask->cleanAction();

        $groupIndexTask = new GroupIndexTask();
        $groupIndexTask->cleanAction();

        $questionIndexTask = new QuestionIndexTask();
        $questionIndexTask->cleanAction();

        $userIndexTask = new UserIndexTask();
        $userIndexTask->cleanAction();
    }

    protected function isDemoEnv()
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById(100015);

        return $user ? true : false;
    }

}
