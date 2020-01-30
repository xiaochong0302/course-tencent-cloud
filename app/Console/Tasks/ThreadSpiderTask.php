<?php

namespace App\Console\Tasks;

use App\Models\Course as CourseModel;
use App\Models\Thread as ThreadModel;
use App\Models\User as UserModel;
use Phalcon\Cli\Task;
use QL\QueryList;

class ThreadSpiderTask extends Task
{

    public function mainAction()
    {
        $courses = CourseModel::query()
            ->columns(['id'])
            ->where('id > 494')
            ->orderBy('id ASC')
            ->execute();

        $ql = $this->getRules();

        foreach ($courses as $course) {
            $this->handleList($ql, $course->id);
            sleep(5);
        }
    }

    protected function getRules()
    {
        $ql = QueryList::rules([
            'user_link' => ['a.media', 'href'],
            'user_img' => ['a.media > img', 'src'],
            'user_name' => ['a.media', 'title'],
            //'chapter_link' => ['div.l-box > a:eq(1)', 'href'],
            'thread_link' => ['a.qa-tit', 'href'],
            'thread_title' => ['a.qa-tit', 'text'],
            'thread_time' => ['em.r', 'text'],
        ]);

        return $ql;
    }

    protected function handleList($ql, $courseId)
    {

        foreach (range(1, 10) as $page) {

            $url = "https://www.imooc.com/course/qa/id/{$courseId}/t/2?page={$page}";

            echo "============== Course {$courseId}, Page {$page} =================" . PHP_EOL;

            $data = $ql->get($url)->query()->getData();

            if ($data->count() == 0) {
                break;
            }

            foreach ($data->all() as $item) {

                $userData = [
                    'id' => $this->getUserId($item['user_link']),
                    'name' => $this->getUserName($item['user_name']),
                    'avatar' => $item['user_img'],
                ];

                $user = UserModel::findFirst($userData['id']);

                if (!$user) {
                    $user = new UserModel();
                    $user->create($userData);
                }

                $threadData = [
                    'course_id' => $courseId,
                    'author_id' => $user->id,
                    'id' => $this->getThreadId($item['thread_link']),
                    'title' => $this->getThreadTitle($item['thread_title']),
                    'created_at' => $this->getThreadTime($item['thread_time']),
                ];

                $thread = ThreadModel::findFirst($threadData['id']);

                if (!$thread) {
                    $thread = new ThreadModel();
                    $thread->create($threadData);
                }
            }
        }

        $ql->destruct();
    }

    protected function getUserId($userLink)
    {
        $result = str_replace(['/u/', '/courses'], '', $userLink);

        return trim($result);
    }

    protected function getUserName($userName)
    {
        $result = mb_substr($userName, 0, 30);

        return $result;
    }

    protected function getChapterId($chapterLink)
    {
        $result = str_replace(['/video/'], '', $chapterLink);

        return trim($result);
    }

    protected function getThreadId($threadLink)
    {
        $result = str_replace(['/qadetail/'], '', $threadLink);

        return trim($result);
    }

    protected function getThreadTitle($title)
    {
        $title = $this->filter->sanitize($title, ['trim']);
        $result = mb_substr($title, 0, 120);
        return $result;
    }

    protected function getThreadTime($time)
    {
        $date = $this->filter->sanitize($time, ['trim', 'string']);

        if (strpos($date, '天')) {
            $days = str_replace(['天前'], '', $date);
            $days = intval($days);
            $result = strtotime("-{$days} days");
        } else {
            $result = strtotime(trim($date));
        }

        return $result;
    }

}
