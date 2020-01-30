<?php

namespace App\Console\Tasks;

use App\Models\Chapter as ChapterModel;
use App\Models\Consult as ConsultModel;
use App\Models\Course as CourseModel;
use App\Models\CoursePackage as CoursePackageModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\Package as PackageModel;
use App\Models\Review as ReviewModel;
use App\Models\User as UserModel;
use App\Repos\CoursePackage as CoursePackageRepo;
use Phalcon\Cli\Task;
use QL\QueryList;

class ClassSpiderTask extends Task
{

    public function listAction()
    {
        $ql = QueryList::rules([
            'course_link' => ['div.shizhan-course-wrap > a', 'href'],
            'course_cover' => ['div.img-box > img.shizhan-course-img', 'src'],
            'course_title' => ['div.img-box > img.shizhan-course-img', 'alt'],
        ]);

        $this->handleList($ql);
    }

    protected function handleList($ql)
    {
        foreach (range(1, 6) as $page) {

            $url = "https://coding.imooc.com/?sort=0&unlearn=0&page={$page}";

            echo "============== Page {$page} =================" . PHP_EOL;

            $data = $ql->get($url)->query()->getData();

            if ($data->count() == 0) {
                break;
            }

            foreach ($data->all() as $item) {

                $courseData = [
                    'class_id' => $this->getCourseId($item['course_link']),
                    'title' => $item['course_title'],
                    'cover' => $item['course_cover'],
                ];

                //print_r($courseData);

                if ($courseData['class_id']) {
                    $course = CourseModel::findFirstByClassId($courseData['class_id']);
                    if (!$course) {
                        $course = new CourseModel();
                        $course->create($courseData);
                    }
                }
            }

        }

        $ql->destruct();
    }

    public function courseAction()
    {
        $courses = CourseModel::query()
            ->where('class_id > 114')
            ->orderBy('class_id ASC')
            ->execute();

        foreach ($courses as $course) {
            $this->handleCourse($course);
            sleep(5);
        }
    }

    public function chapterAction()
    {
        $ql = QueryList::rules([
            'chapter_title' => ['.chapter-bd > h5.name', 'text'],
            'chapter_summary' => ['.chapter-bd > p.desc', 'text'],
            'lesson_html' => ['.chapter-bd > ul', 'html'],
        ]);

        $courses = CourseModel::query()
            ->where('class_id > 114')
            ->orderBy('class_id ASC')
            ->execute();

        foreach ($courses as $course) {
            $this->handleChapter($ql, $course);
            sleep(5);
        }
    }

    protected function handleChapter(QueryList $ql, $course)
    {
        echo " course id: {$course->id} , class_id :{$course->class_id} " . PHP_EOL;

        $url = "https://coding.imooc.com/class/chapter/{$course->class_id}.html";

        $data = $ql->get($url)->query()->getData();

        if ($data->count() == 0) {
            return;
        }

        $lesson_ql = QueryList::rules([
            'lesson_title' => ['span.title_info', 'text'],
            'lesson_free' => ['span.watch-free', 'text'],
        ]);

        foreach ($data->all() as $item) {

            $chapterData = [
                'course_id' => $course->id,
                'title' => trim($item['chapter_title']),
                'summary' => trim($item['chapter_summary']),
            ];

            $chapter = new ChapterModel();
            $chapter->create($chapterData);

            $this->handleLesson($chapter, $lesson_ql, $item['lesson_html']);
        }

        $ql->destruct();
    }

    protected function handleLesson($chapter, QueryList $lesson_ql, $html)
    {

        $lessons = $lesson_ql->html($html)->query()->getData();

        if ($lessons->count() == 0) {
            return;
        }

        foreach ($lessons->all() as $item) {
            $data = [
                'course_id' => $chapter->course_id,
                'parent_id' => $chapter->id,
                'title' => $item['lesson_title'],
                'free' => $item['lesson_free'] ? 1 : 0,
            ];

            $model = new ChapterModel();
            $model->create($data);
        }

        $lesson_ql->destruct();
    }

    public function consultAction()
    {
        $courses = CourseModel::query()
            ->where('class_id > 0')
            ->orderBy('class_id ASC')
            ->execute();

        foreach ($courses as $course) {
            $this->handleConsult($course);
            sleep(5);
        }
    }

    protected function handleConsult($course)
    {

        foreach (range(1, 20) as $page) {

            echo "course {$course->id}, page {$page}" . PHP_EOL;

            $url = "https://coding.imooc.com/class/ajaxconsultsearch?cid={$course->class_id}&page={$page}&pagesize=15";

            $content = file_get_contents($url);

            $json = json_decode($content, true);

            $consults = $json['data']['data_adv'];

            if (empty($consults)) {
                break;
            }

            foreach ($consults as $item) {
                $data = [
                    'question' => $item['content'],
                    'answer' => $item['answer'],
                    'like_count' => $item['praise'],
                    'created_at' => strtotime($item['create_time']),
                ];
                $consult = new ConsultModel();
                $consult->create($data);
            }

        }

    }

    public function reviewAction()
    {
        $ql = QueryList::rules([
            'review_content' => ['p.cmt-txt', 'text'],
            'review_rating' => ['div.stars > span', 'text'],
        ]);

        $courses = CourseModel::query()
            ->where('class_id > 0')
            ->orderBy('class_id ASC')
            ->execute();

        foreach ($courses as $course) {
            $this->handleReview($ql, $course);
            sleep(5);
        }
    }

    protected function handleReview($ql, $course)
    {
        foreach (range(1, 10) as $page) {

            $url = "https://coding.imooc.com/class/evaluation/{$course->class_id}.html?page={$page}";

            echo "============== Course {$course->id}, Page {$page} =================" . PHP_EOL;

            $data = $ql->get($url)->query()->getData();

            if ($data->count() == 0) {
                break;
            }

            foreach ($data->all() as $item) {

                $reviewData = [
                    'course_id' => $course->id,
                    'content' => $item['review_content'],
                    'rating' => $this->getReviewRating($item['review_rating']),
                ];

                $review = new ReviewModel();
                $review->create($reviewData);
            }
        }

        $ql->destruct();
    }

    public function packageAction()
    {
        $ql = QueryList::rules([
            'id' => ['a.js-buy-package', 'data-cid'],
            'title' => ['p.package-title', 'text'],
            'price' => ['p.package-price', 'text'],
            'other_html' => ['div.other-course-wrap', 'html'],
        ]);

        $courses = CourseModel::query()
            ->where('class_id > 0')
            ->orderBy('class_id ASC')
            ->execute();

        foreach ($courses as $course) {
            $this->handlePackage($ql, $course);
            sleep(5);
        }
    }

    protected function handlePackage(QueryList $ql, $course)
    {
        echo " course id: {$course->id} , class_id :{$course->class_id} " . PHP_EOL;

        $url = "https://coding.imooc.com/class/package/{$course->class_id}.html";

        $data = $ql->get($url)->query()->getData();

        if ($data->count() == 0) {
            return;
        }

        $other_ql = QueryList::rules([
            'href' => ['a.course-item', 'href'],
        ]);

        foreach ($data->all() as $item) {

            $packageData = [
                'id' => trim($item['id']),
                'title' => trim($item['title']),
                'market_price' => $this->getMarketPrice($item['price']),
            ];

            $package = PackageModel::findFirst($packageData['id']);

            if (!$package) {
                $package = new PackageModel();
                $package->create($packageData);
            }

            $cpRepo = new CoursePackageRepo();

            $cp = $cpRepo->findCoursePackage($course->id, $package->id);

            if (!$cp) {
                $cp = new CoursePackageModel();
                $cp->course_id = $course->id;
                $cp->package_id = $package->id;
                $cp->create();
            }

            $this->handleOtherPackageCourse($package, $other_ql, $item['other_html']);
        }

        $ql->destruct();
    }

    protected function handleOtherPackageCourse($package, QueryList $other_ql, $html)
    {
        $courses = $other_ql->html($html)->query()->getData();

        if ($courses->count() == 0) {
            return;
        }

        foreach ($courses->all() as $item) {

            $courseId = str_replace(['//coding.imooc.com/class/', '.html'], '', $item['href']);

            $cpRepo = new CoursePackageRepo();

            $cp = $cpRepo->findCoursePackage($courseId, $package->id);

            if (!$cp) {
                $cp = new CoursePackageModel();
                $cp->course_id = (int)$courseId;
                $cp->package_id = $package->id;
                $cp->create();
            }
        }

        $other_ql->destruct();
    }

    protected function handleCourse($course)
    {
        echo " =============== class id {$course->class_id} ============" . PHP_EOL;

        $url = "https://coding.imooc.com/class/{$course->class_id}.html";

        $ql = QueryList::getInstance()->get($url);

        $summary = $ql->find('div.info-desc')->text();
        $userLink = $ql->find('div.teacher > a')->attr('href');
        $marketPrice = $ql->find('div.ori-price')->text();
        $level = $ql->find('div.info-bar > span:eq(1)')->text();
        $duration = $ql->find('div.info-bar > span:eq(3)')->text();
        $userCount = $ql->find('div.info-bar > span:eq(5)')->text();
        $score = $ql->find('div.info-bar > span:eq(7)')->text();

        $courseData = [
            'summary' => trim($summary),
            'user_count' => intval($userCount),
            'market_price' => $this->getMarketPrice($marketPrice),
            'level' => $this->getLevel($level),
            'score' => $this->getScore($score),
            'attrs' => [
                'duration' => $this->getCourseDuration($duration),
            ],
        ];

        $course->update($courseData);

        $ql->destruct();

        $userId = $this->getUserId($userLink);

        $user = UserModel::findFirst($userId);

        if ($user) {

            $user->edu_role = UserModel::EDU_ROLE_TEACHER;
            $user->update();

            $cuRepo = new \App\Repos\CourseUser();

            $row = $cuRepo->findCourseTeacher($course->id, $user->id);

            if (!$row) {
                $courseUser = new CourseUserModel();
                $courseUser->course_id = $course->id;
                $courseUser->user_id = $user->id;
                $courseUser->role_type = CourseUserModel::ROLE_TEACHER;
                $courseUser->expire_time = strtotime('+15 years');
                $courseUser->create();
            }
        }

        $this->handleTeacherInfo($userId);

    }

    protected function handleTeacherInfo($id)
    {
        $url = 'http://www.imooc.com/t/' . $id;

        $ql = QueryList::getInstance()->get($url);

        $data = [];

        $data['id'] = $id;
        $data['avatar'] = $ql->find('img.tea-header')->attr('src');
        $data['name'] = $ql->find('p.tea-nickname')->text();
        $data['title'] = $ql->find('p.tea-professional')->text();
        $data['about'] = $ql->find('p.tea-desc')->text();

        $user = UserModel::findFirst($id);

        if (!$user) {
            $user = new UserModel();
            $user->create($data);
        }

        $ql->destruct();
    }

    protected function getUserId($userLink)
    {
        $result = str_replace(['http://www.imooc.com/u/'], '', $userLink);

        return trim($result);
    }

    protected function getCourseId($courseLink)
    {
        if (!strpos($courseLink, '.html')) {
            return false;
        }

        $result = str_replace(['/class/', '.html'], '', $courseLink);

        return trim($result);
    }

    protected function getMarketPrice($price)
    {
        $price = str_replace('￥', '', $price);
        return floatval(trim($price));
    }

    protected function getScore($score)
    {
        return floatval(trim($score) * 10);
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

    protected function getReviewRating($type)
    {
        $mapping = [
            '好评' => 10,
            '中评' => 8,
            '差评' => 6,
        ];

        return $mapping[$type] ?? 8;
    }

}
