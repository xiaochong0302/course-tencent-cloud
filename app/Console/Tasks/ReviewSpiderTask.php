<?php

namespace App\Console\Tasks;

use App\Models\Course as CourseModel;
use App\Models\Review as ReviewModel;
use App\Models\User as UserModel;
use App\Repos\Review as ReviewRepo;
use Phalcon\Cli\Task;
use QL\QueryList;

class ReviewSpiderTask extends Task
{

    const BASE_URL = 'https://www.imooc.com';

    public function mainAction()
    {
        $courses = CourseModel::query()
            ->columns(['id'])
            ->where('id > 778')
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
            'user_link' => ['a.img-box', 'href'],
            'user_img' => ['a.img-box > img', 'src'],
            'user_name' => ['a.img-box > img', 'alt'],
            'review_content' => ['p.content', 'text'],
            'review_rating' => ['div.star-box > span', 'text'],
        ]);

        return $ql;
    }

    protected function handleList($ql, $courseId)
    {

        foreach (range(1, 7) as $page) {

            $url = "https://www.imooc.com/course/coursescore/id/{$courseId}?page={$page}";

            echo "============== Course {$courseId}, Page {$page} =================" . PHP_EOL;

            $data = $ql->get($url)->query()->getData();

            if ($data->count() == 0) {
                continue;
            }

            foreach ($data->all() as $item) {

                $userData = [
                    'id' => $this->getUserId($item['user_link']),
                    'name' => $item['user_name'],
                    'avatar' => $item['user_img'],
                ];

                $user = UserModel::findFirst($userData['id']);

                if (!$user) {
                    $user = new UserModel();
                    $user->create($userData);
                }

                $reviewData = [
                    'user_id' => $user->id,
                    'course_id' => $courseId,
                    'content' => $this->getReviewContent($item['review_content']),
                    'rating' => $this->getReviewRating($item['review_rating']),
                ];

                $reviewRepo = new ReviewRepo();

                $reviewExist = $reviewRepo->findReview($courseId, $user->id);

                if (!$reviewExist) {
                    $review = new ReviewModel();
                    $review->create($reviewData);
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

    protected function getReviewRating($rating)
    {
        $result = str_replace(['分'], '', $rating);

        return intval($result);
    }

    protected function getReviewContent($content)
    {
        $result = $this->filter->sanitize($content, ['trim', 'string']);

        return $result;
    }
}
