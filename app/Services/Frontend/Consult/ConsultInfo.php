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
            'rating' => $consult->rating,
            'like_count' => $consult->like_count,
            'create_time' => $consult->create_time,
            'update_time' => $consult->update_time,
            'course' => new \stdClass(),
            'chapter' => new \stdClass(),
            'owner' => new \stdClass(),
        ];

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($consult->course_id);

        if ($course) {
            $result['course'] = [
                'id' => $course->id,
                'title' => $course->title,
            ];
        }

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($consult->chapter_id);

        if ($chapter) {
            $result['chapter'] = [
                'id' => $chapter->id,
                'title' => $chapter->title,
            ];
        }

        $userRepo = new UserRepo();

        $owner = $userRepo->findById($consult->owner_id);

        if ($owner) {
            $result['owner'] = [
                'id' => $owner->id,
                'name' => $owner->name,
                'avatar' => $owner->avatar,
            ];
        }

        return $result;
    }

}
