<?php

namespace App\Services\Frontend\Consult;

use App\Models\Consult as ConsultModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;
use App\Services\Frontend\ConsultTrait;
use App\Services\Frontend\Service as FrontendService;

class ConsultInfo extends FrontendService
{

    use ConsultTrait;

    public function handle($id)
    {
        $consult = $this->checkConsult($id);

        return $this->handleConsult($consult);
    }

    protected function handleConsult(ConsultModel $consult)
    {
        $result = [
            'id' => $consult->id,
            'question' => $consult->question,
            'answer' => $consult->answer,
            'private' => $consult->private,
            'like_count' => $consult->like_count,
            'create_time' => $consult->create_time,
            'update_time' => $consult->update_time,
        ];

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($consult->course_id);

        $result['course'] = [
            'id' => $course->id,
            'title' => $course->title,
        ];

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($consult->chapter_id);

        $result['chapter'] = [
            'id' => $chapter->id,
            'title' => $chapter->title,
        ];

        $userRepo = new UserRepo();

        $user = $userRepo->findById($consult->user_id);

        $result['user'] = [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
        ];

        return $result;
    }

}
