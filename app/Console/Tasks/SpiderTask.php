<?php

namespace App\Console\Tasks;

use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use Phalcon\Cli\Task;
use QL\QueryList;

class SpiderTask extends Task
{

    public function testAction()
    {
        $subject = '1-1 课程简介(01:40)开始学习';
        preg_match('/(\d{1,}-\d{1,})\s{1,}(.*?)\((.*?)\)/', $subject, $matches);
        dd($matches);
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
            ->where('1 = 1')
            ->andWhere('id > :id:', ['id' => 1128])
            ->orderBy('id asc')
            ->execute();

        $baseUrl = 'http://www.imooc.com/learn';

        $instance = QueryList::getInstance();

        foreach ($courses as $course) {
            $url = $baseUrl . '/' . $course->id;
            $ql = $instance->get($url);
            $result = $this->handleCourseInfo($course, $ql);
            if (!$result) {
                continue;
            }
            $this->handleCourseChapters($course, $ql);
            echo "finished course " . $course->id . PHP_EOL;
            sleep(1);
        }
    }

    public function teacherAction()
    {
        $courses = CourseModel::query()
            ->where('1 = 1')
            ->groupBy('user_id')
            ->execute();

        foreach ($courses as $course) {
            $this->handleTeacherInfo($course->user_id);
            echo "finished teacher: {$course->user_id}" . PHP_EOL;
            sleep(1);
        }
    }

    public function userAction()
    {
        $users = UserModel::query()
            ->where('1 = 1')
            ->andWhere('name = :name:', ['name' => ''])
            ->execute();

        foreach ($users as $user) {
            $this->handleUserInfo($user->id);
            echo "finished user: {$user->id}" . PHP_EOL;
            sleep(1);
        }
    }

    protected function handleUserInfo($id)
    {
        $url = 'http://www.imooc.com/u/'. $id;

        $ql = QueryList::getInstance()->get($url);

        $data = [];

        $data['id'] = $id;
        $data['avatar'] = $ql->find('.user-pic-bg>img')->attr('src');
        $data['name'] = $ql->find('h3.user-name>span')->text();
        $data['about'] = $ql->find('p.user-desc')->text();

        $user = new UserModel();

        $user->save($data);
    }

    protected function handleTeacherInfo($id)
    {
        $url = 'http://www.imooc.com/t/'. $id;

        $ql = QueryList::getInstance()->get($url);

        $data = [];

        $data['id'] = $id;
        $data['avatar'] = $ql->find('img.tea-header')->attr('src');
        $data['name'] = $ql->find('p.tea-nickname')->text();
        $data['title'] = $ql->find('p.tea-professional')->text();
        $data['about'] = $ql->find('p.tea-desc')->text();

        $user = new UserModel();

        $user->create($data);
    }

    protected function handleCourseInfo(CourseModel $course, QueryList $ql)
    {
        $data = [];

        $data['user_id'] = $ql->find('img.js-usercard-dialog')->attr('data-userid');
        $data['description'] = $ql->find('.course-description')->text();
        $data['duration'] = $ql->find('.static-item:eq(1)>.meta-value')->text();
        $data['score'] = $ql->find('.score-btn>.meta-value')->text();

        if (empty($data['user_id'])) {
            return false;
        }

        $data['duration'] = $this->getCourseDuration($data['duration']);

        return $course->update($data);
    }

    protected function handleCourseChapters(CourseModel $course, QueryList $ql)
    {
        $topChapters = $ql->rules([
            'title' => ['.chapter>h3', 'text'],
            'sub_chapter_html' => ['.chapter>.video', 'html'],
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
            preg_match('/(\d{1,}-\d{1,})\s{1,}(.*?)\((.*?)\)/s', $item, $matches);
            if (!isset($matches[3]) || empty($matches[3])) {
                continue;
            }
            $data = [
                'course_id' => $topChapter->course_id,
                'parent_id' => $topChapter->id,
                'title' => $matches[2],
                'duration' => $this->getChapterDuration($matches[3]),
            ];
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
            '中级' => CourseModel::LEVEL_MIDDLE,
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
