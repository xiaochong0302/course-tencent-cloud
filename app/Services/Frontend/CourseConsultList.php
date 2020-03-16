<?php

namespace App\Services\Frontend;

use App\Builders\ConsultList as ConsultListBuilder;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\ConsultVote as ConsultVoteModel;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\Consult as ConsultRepo;
use App\Repos\Course as CourseRepo;
use Phalcon\Mvc\Model\Resultset;

class CourseConsultList extends Service
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

    public function getConsults($courseId)
    {
        $this->course = $this->checkCourse($courseId);

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

        $votes = $this->getConsultVotes($this->course->id, $this->user->id);

        $items = [];

        foreach ($consults as $consult) {

            $user = $users[$consult['user_id']] ?? [];

            $me = [
                'agreed' => $votes[$consult['id']]['agreed'] ?? false,
                'opposed' => $votes[$consult['id']]['opposed'] ?? false,
            ];

            $items[] = [
                'id' => $consult['id'],
                'question' => $consult['question'],
                'answer' => $consult['answer'],
                'agree_count' => $consult['agree_count'],
                'oppose_count' => $consult['oppose_count'],
                'created_at' => $consult['created_at'],
                'user' => $user,
                'me' => $me,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function getConsultVotes($courseId, $userId)
    {
        if (!$courseId || !$userId) {
            return [];
        }

        $courseRepo = new CourseRepo();

        /**
         * @var Resultset $votes
         */
        $votes = $courseRepo->findUserConsultVotes($courseId, $userId);

        if ($votes->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($votes as $vote) {
            $result[$vote->consult_id] = [
                'agreed' => $vote->type == ConsultVoteModel::TYPE_AGREE,
                'opposed' => $vote->type == ConsultVoteModel::TYPE_OPPOSE,
            ];
        }

        return $result;
    }

}
