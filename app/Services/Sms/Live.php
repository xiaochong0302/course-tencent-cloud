<?php

namespace App\Services\Sms;

use App\Repos\Account as AccountRepo;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Course as CourseRepo;
use App\Services\Smser;

class Live extends Smser
{

    protected $templateCode = 'live';

    /**
     * @param int $chapterId
     * @param int $userId
     * @param int $startTime
     * @return bool
     */
    public function handle($chapterId, $userId, $startTime)
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($userId);

        if (empty($account->phone)) {
            return false;
        }

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($chapterId);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($chapter->course_id);

        $params = [
            $course->title,
            $chapter->title,
            $startTime,
        ];

        $templateId = $this->getTemplateId($this->templateCode);

        return $this->send($account->phone, $templateId, $params);
    }

}
