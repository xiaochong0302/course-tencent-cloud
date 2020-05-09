<?php

namespace App\Services\Frontend\Course;

use App\Builders\ConsultList as ConsultListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\ConsultVote as ConsultVoteModel;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\Consult as ConsultRepo;
use App\Repos\Course as CourseRepo;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service;

class ConsultList extends Service
{

    /**
     * @var CourseModel
     */
    protected $course;

    /**
     * @var UserModel
     */
    protected $user;

    use CourseTrait;

    public function handle($id)
    {
        $this->course = $this->checkCourse($id);

        $this->user = $this->getCurrentUser();

        $pagerQuery = new PagerQuery();

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $params = [
            'course_id' => $this->course->id,
            'published' => 1,
            'deleted' => 0,
        ];

        $consultRepo = new ConsultRepo();

        $pager = $consultRepo->paginate($params, $sort, $page, $limit);

        return $this->handleConsults($pager);
    }

    protected function handleConsults($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $consults = $pager->items->toArray();

        $builder = new ConsultListBuilder();

        $users = $builder->getUsers($consults);

        $votes = $this->getConsultVotes($this->course, $this->user);

        $items = [];

        foreach ($consults as $consult) {

            $user = $users[$consult['user_id']] ?? new \stdClass();

            $me = [
                'agreed' => $votes[$consult['id']]['agreed'] ?? 0,
                'opposed' => $votes[$consult['id']]['opposed'] ?? 0,
            ];

            $items[] = [
                'id' => $consult['id'],
                'question' => $consult['question'],
                'answer' => $consult['answer'],
                'agree_count' => $consult['agree_count'],
                'oppose_count' => $consult['oppose_count'],
                'create_time' => $consult['create_time'],
                'user' => $user,
                'me' => $me,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function getConsultVotes(CourseModel $course, UserModel $user)
    {
        if ($course->id == 0 || $user->id == 0) {
            return [];
        }

        $courseRepo = new CourseRepo();

        $votes = $courseRepo->findUserConsultVotes($course->id, $user->id);

        if ($votes->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($votes as $vote) {
            $result[$vote->consult_id] = [
                'agreed' => $vote->type == ConsultVoteModel::TYPE_AGREE ? 1 : 0,
                'opposed' => $vote->type == ConsultVoteModel::TYPE_OPPOSE ? 1 : 0,
            ];
        }

        return $result;
    }

}
