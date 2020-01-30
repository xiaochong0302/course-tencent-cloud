<?php

namespace App\Console\Tasks;

use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\User as UserModel;
use App\Repos\CourseUser as CourseUserRepo;
use Phalcon\Cli\Task;
use QL\QueryList;

class SpiderTask extends Task
{

    public function ctAction()
    {
        $courses = CourseModel::query()
            ->where('class_id = 0')
            ->execute();

        foreach ($courses as $course) {

            $url = "http://www.imooc.com/learn/{$course->id}";

            $ql = QueryList::getInstance()->get($url);

            $userId = $ql->find('img.js-usercard-dialog')->attr('data-userid');

            if ($userId) {
                $user = UserModel::findFirst($userId);
                if (!$user || !$user->avatar) {
                    $this->handleUserInfo2($course->id, $userId);
                }
            }

            $ql->destruct();

            echo "finished course " . $course->id . PHP_EOL;
        }
    }

    public function user2Action()
    {
        $users = UserModel::query()
            ->where('edu_role = 2')
            ->andWhere('name = :name:', ['name' => ''])
            ->execute();

        foreach ($users as $user) {
            $this->handleUserInfo($user->id);
            echo "finished user: {$user->id}" . PHP_EOL;
        }
    }

    public function courseListAction($params)
    {
        $category = $params[0] ?? 'html';
        $page = $params[1] ?? 1;

        $categoryId = $this->getCategoryId($category);

        if (empty($categoryId)) {
            throw new \Exception('invalid category');
        }

        $url = "http://www.imooc.com/course/list?c={$category}&page={$page}";

        $data = QueryList::get($url)->rules([
            'link' => ['a.course-card', 'href'],
            'title' => ['h3.course-card-name', 'text'],
            'cover' => ['img.course-banner', 'data-original'],
            'summary' => ['p.course-card-desc', 'text'],
            'level' => ['.course-card-info>span:even', 'text'],
            'user_count' => ['.course-card-info>span:odd', 'text'],
        ])->query()->getData();

        if ($data->count() == 0) {
            return false;
        }

        foreach ($data->all() as $item) {
            $course = [
                'id' => substr($item['link'], 7),
                'category_id' => $categoryId,
                'title' => $item['title'],
                'cover' => $item['cover'],
                'summary' => $item['summary'],
                'user_count' => $item['user_count'],
                'level' => $this->getLevel($item['level']),
            ];
            $model = new CourseModel();
            $model->save($course);
        }

        echo sprintf("saved: %d course", $data->count());
    }

    public function courseAction()
    {
        $courses = CourseModel::query()
            ->where('id = 762')
            ->orderBy('id asc')
            ->execute();

        $instance = QueryList::getInstance();

        foreach ($courses as $course) {

            $url = "http://www.imooc.com/learn/{$course->id}";

            $ql = $instance->get($url);

            //$this->handleCourseInfo($course, $ql);

            $this->handleCourseChapters($course, $ql);

            //$ql->destruct();

            echo "finished course " . $course->id . PHP_EOL;
        }
    }

    public function teacherAction()
    {
        $users = UserModel::query()
            ->where('edu_role = 2')
            ->execute();

        foreach ($users as $user) {
            try {
                $this->handleTeacherInfo2($user);
                echo "finished teacher: {$user->id}" . PHP_EOL;
            } catch (\Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

    protected function handleTeacherInfo2(UserModel $user)
    {
        $url = "http://www.imooc.com/t/{$user->id}";

        $ql = QueryList::getInstance()->get($url);

        $data = [];

        $data['avatar'] = $ql->find('img.tea-header')->attr('src');
        $data['name'] = $ql->find('p.tea-nickname')->text();
        $data['title'] = $ql->find('p.tea-professional')->text();
        $data['about'] = $ql->find('p.tea-desc')->text();

        $user->update($data);
    }

    public function userAction()
    {
        $users = UserModel::query()
            ->where('edu_role = 1')
            ->execute();

        foreach ($users as $user) {
            $this->handleUserInfo($user->id);
            echo "finished user: {$user->id}" . PHP_EOL;
        }
    }

    protected function handleUserInfo($id)
    {
        $url = 'https://www.imooc.com/u/' . $id;

        $user = UserModel::findFirst($id);

        try {

            $ql = QueryList::getInstance()->get($url);

            $data = [];

            $data['avatar'] = $ql->find('.user-pic-bg>img')->attr('src');
            $data['name'] = $ql->find('h3.user-name>span')->text();
            $data['about'] = $ql->find('p.user-desc')->text();

            print_r($data);

            $user->update($data);

            $ql->destruct();

        } catch (\Exception $e) {

            $user->update(['deleted' => 1]);

            echo "user {$id} not found" . PHP_EOL;
        }
    }

    protected function handleUserInfo2($courseId, $userId)
    {
        $url = 'https://www.imooc.com/u/' . $userId;

        $user = UserModel::findFirst($userId);

        try {

            $ql = QueryList::getInstance()->get($url);

            $data = [];

            $data['avatar'] = $ql->find('.user-pic-bg>img')->attr('src');
            $data['name'] = $ql->find('h3.user-name>span')->text();
            $data['about'] = $ql->find('p.user-desc')->text();
            $data['edu_role'] = UserModel::EDU_ROLE_TEACHER;

            if ($user) {
                $user->update($data);
            } else {
                $user = new UserModel();
                $user->create($data);
            }

            $cuRepo = new CourseUserRepo();

            $courseUser = $cuRepo->findCourseUser($courseId, $userId);

            if (!$courseUser) {
                $courseUser = new CourseUserModel();
                $courseUser->course_id = $courseId;
                $courseUser->user_id = $userId;
                $courseUser->role_type = CourseUserModel::ROLE_TEACHER;
                $courseUser->expire_time = strtotime('+15 years');
                $courseUser->create();
            }

            echo "teacher {$userId} off " . PHP_EOL;

            $ql->destruct();

        } catch (\Exception $e) {

            $user->update(['deleted' => 1]);

            echo "user {$userId} not found" . PHP_EOL;
        }
    }

    protected function handleTeacherInfo($courseId, $userId)
    {
        $url = "http://www.imooc.com/t/{$userId}";

        $ql = QueryList::getInstance()->get($url);

        $data = [];

        $data['id'] = $userId;
        $data['avatar'] = $ql->find('img.tea-header')->attr('src');
        $data['name'] = $ql->find('p.tea-nickname')->text();
        $data['title'] = $ql->find('p.tea-professional')->text();
        $data['about'] = $ql->find('p.tea-desc')->text();
        $data['edu_role'] = UserModel::EDU_ROLE_TEACHER;

        $user = UserModel::findFirst($userId);

        if ($user) {
            $user->update($data);
        } else {
            $user = new UserModel();
            $user->create($data);
        }

        $cuRepo = new CourseUserRepo();

        $courseUser = $cuRepo->findCourseUser($courseId, $userId);

        if (!$courseUser) {
            $courseUser = new CourseUserModel();
            $courseUser->course_id = $courseId;
            $courseUser->user_id = $userId;
            $courseUser->role_type = CourseUserModel::ROLE_TEACHER;
            $courseUser->expire_time = strtotime('+15 years');
            $courseUser->create();
        }

        $ql->destruct();

        echo "teacher ok" . PHP_EOL;
    }

    protected function handleCourseInfo(CourseModel $course, QueryList $ql)
    {
        $data = [];

        $data['user_id'] = $ql->find('img.js-usercard-dialog')->attr('data-userid');
        $data['description'] = $ql->find('.course-description')->text();
        $data['duration'] = $ql->find('.static-item:eq(1)>.meta-value')->text();
        $data['score'] = $ql->find('.score-btn>.meta-value')->text();

        $data['attrs']['duration'] = $this->getCourseDuration($data['duration']);

        $course->update($data);

        if ($data['user_id']) {
            $this->handleTeacherInfo($course->id, $data['user_id']);
        }

        echo "course info ok" . PHP_EOL;
    }

    protected function handleCourseChapters(CourseModel $course, QueryList $ql)
    {
        echo "top chapter" . PHP_EOL;

        $topChapters = $ql->rules([
            'title' => ['.chapter > h3', 'text'],
            'sub_chapter_html' => ['.chapter > .video', 'html'],
        ])->query()->getData();


        if ($topChapters->count() == 0) {
            return false;
        }

        foreach ($topChapters->all() as $item) {

            $data = [
                'course_id' => $course->id,
                'title' => $item['title'],
            ];

            // create top chapter
            $chapter = new ChapterModel();
            $chapter->create($data);

            // create sub chapter
            if (!empty($item['sub_chapter_html'])) {
                $this->handleSubChapters($chapter, $item['sub_chapter_html']);
            }
        }
    }

    protected function handleSubChapters(ChapterModel $topChapter, $subChapterHtml)
    {
        $ql = QueryList::html($subChapterHtml);

        $chapters = $ql->find('li')->texts();

        if ($chapters->count() == 0) {
            return false;
        }

        foreach ($chapters->all() as $item) {

            /**
             *
             * preg_match('/(\d{1,}-\d{1,})\s{1,}(.*?)\((.*?)\)/s', $item, $matches);
             *
             * if (!isset($matches[3]) || empty($matches[3])) {
             * continue;
             * }
             *
             * $data = [
             * 'title' => $matches[2],
             * 'duration' => $this->getChapterDuration($matches[3]),
             * ];
             */

            $title = str_replace(["开始学习", "\r", "\n", "\t", "  "], "", $item);
            $title = preg_replace('/\(\d{2}:\d{2}\)/', '', $title);

            $data = [];
            $data['course_id'] = $topChapter->course_id;
            $data['parent_id'] = $topChapter->id;
            $data['title'] = trim($title);

            $model = new ChapterModel();
            $model->create($data);
        }
    }

    protected function getCourseDuration($duration)
    {
        $hours = 0;
        $minutes = 0;

        if (preg_match('/(.*?)小时(.*?)分/s', $duration, $matches)) {
            $hours = trim($matches[1]);
            $minutes = trim($matches[2]);
        } elseif (preg_match('/(.*?)小时/s', $duration, $matches)) {
            $hours = trim($matches[1]);
        } elseif (preg_match('/(.*?)分/s', $duration, $matches)) {
            $minutes = trim($matches[1]);
        }

        return 3600 * $hours + 60 * $minutes;
    }

    protected function getChapterDuration($duration)
    {
        if (strpos($duration, ':') === false) {
            return 0;
        }

        list($minutes, $seconds) = explode(':', trim($duration));

        return 60 * $minutes + $seconds;
    }

    protected function getLevel($type)
    {
        $mapping = [
            '入门' => CourseModel::LEVEL_ENTRY,
            '初级' => CourseModel::LEVEL_JUNIOR,
            '中级' => CourseModel::LEVEL_MEDIUM,
            '高级' => CourseModel::LEVEL_SENIOR,
        ];

        return $mapping[$type] ?? CourseModel::LEVEL_ENTRY;
    }

    protected function getCategoryId($type)
    {
        $mapping = [
            'html' => 1, 'javascript' => 2, 'vuejs' => 10, 'reactjs' => 19,
            'angular' => 18, 'nodejs' => 16, 'jquery' => 15,
            'bootstrap' => 17, 'sassless' => 21, 'webapp' => 22, 'fetool' => 23,
            'html5' => 13, 'css3' => 14,
            'php' => 24, 'java' => 25, 'python' => 26, 'c' => 27, 'cplusplus' => 28, 'ruby' => 29, 'go' => 30, 'csharp' => 31,
            'android' => 32, 'ios' => 33,
            'mysql' => 36, 'mongodb' => 37, 'redis' => 38, 'oracle' => 39, 'pgsql' => 40,
            'cloudcomputing' => 42, 'bigdata' => 43,
            'unity3d' => 34, 'cocos2dx' => 35,
            'dxdh' => 46, 'uitool' => 47, 'uijc' => 48,
        ];

        return $mapping[$type] ?? 0;
    }

}
