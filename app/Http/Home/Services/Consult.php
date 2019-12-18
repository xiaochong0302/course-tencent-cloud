<?php

namespace App\Http\Home\Services;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Consult as ConsultModel;
use App\Repos\Consult as ConsultRepo;
use App\Repos\ConsultVote as ConsultVoteRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;
use App\Validators\Consult as ConsultFilter;

class Consult extends Service
{
    
    public function create()
    {
        $user = $this->getLoggedUser();

        $post = $this->request->getPost();

        $filter = new ConsultFilter();

        $data = [];

        $data['user_id'] = $user->id;
        $data['course_id'] = $filter->checkCourseId($post['course_id']);
        $data['content'] = $filter->checkContent($post['content']);

        $consultRepo = new ConsultRepo();

        $consult = $consultRepo->create($data);
        
        $courseRepo = new CourseRepo();
        
        $course = $courseRepo->findById($data['course_id']);

        $course->consult_count += 1;

        $course->update();

        return $consult;
    }
    
    public function getConsult($id)
    {
        $consult = $this->findOrFail($id);

        return $this->handleConsult($consult);
    }
    
    public function update($id)
    {
        $consult = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $post = $this->request->getPost();

        $filter = new ConsultFilter();

        $filter->checkOwner($user->id, $consult->user_id);

        $content = $filter->checkContent($post['content']);

        $consult->content = $content;

        return $consult;
    }

    public function delete($id)
    {
        $consult = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $filter = new ConsultFilter();

        $filter->checkOwner($user->id, $consult->user_id);
        
        if ($consult->status == ConsultModel::STATUS_DELETED) {
            return;
        }

        $consult->status = ConsultModel::STATUS_DELETED;
        
        $consult->update();
    }

    public function agree($id)
    {
        $consult = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $voteRepo = new ConsultVoteRepo();

        $vote = $voteRepo->find($user->id, $consult->id);

        if ($vote) {
            throw new BadRequestException('consult.has_voted');
        }

        $voteRepo->agree($user->id, $consult->id);

        $consult->agree_count += 1;

        $consult->update();
    }

    public function oppose($id)
    {
        $consult = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $voteRepo = new ConsultVoteRepo();

        $vote = $voteRepo->find($user->id, $consult->id);

        if ($vote) {
            throw new BadRequestException('consult.has_voted');
        }

        $voteRepo->oppose($user->id, $consult->id);

        $consult->oppose_count += 1;

        $consult->update();
    }

    public function reply($id)
    {
        $consult = $this->findOrFail($id);

        $user = $this->getLoggedUser();

        $post = $this->request->getPost();

        $filter = new ConsultFilter();

        $filter->checkOwner($user->id, $consult->user_id);

        $reply = $filter->checkReply($post['reply']);

        $consult->reply = $reply;
        $consult->reply_time = time();

        $consult->update();
    }

    private function findOrFail($id)
    {
        $repo = new ConsultRepo();

        $result = $repo->findOrFail($id);

        return $result;
    }

    private function handleConsult($consult)
    {
        $result = $consult->toArray();

        $userRepo = new UserRepo();

        $user = $userRepo->findShallowUser($consult->user_id);

        $result['user'] = $user->toArray();

        return (object) $result;
    }

}
