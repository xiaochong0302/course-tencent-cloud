<?php

namespace App\Services\Frontend\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\ChapterVote as ChapterVoteModel;
use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\User as UserModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\ChapterVote as ChapterVoteRepo;
use App\Services\ChapterVod as ChapterVodService;
use App\Services\Frontend\ChapterTrait;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Live as LiveService;

class ChapterInfo extends FrontendService
{

    /**
     * @var CourseModel
     */
    protected $course;

    /**
     * @var UserModel
     */
    protected $user;

    use CourseTrait, ChapterTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapterCache($id);

        $course = $this->checkCourseCache($chapter->course_id);

        $this->course = $course;

        $user = $this->getCurrentUser();

        $this->user = $user;

        $this->setCourseUser($course, $user);
        $this->handleCourseUser($course, $user);

        $this->setChapterUser($chapter, $user);
        $this->handleChapterUser($chapter, $user);

        return $this->handleChapter($chapter, $user);
    }

    protected function handleChapter(ChapterModel $chapter, UserModel $user)
    {
        $result = $this->formatChapter($chapter);

        $result['course'] = $this->handleCourse($this->course);

        $me = [
            'plan_id' => 0,
            'position' => 0,
            'joined' => 0,
            'owned' => 0,
            'agreed' => 0,
            'opposed' => 0,
        ];

        if ($this->courseUser) {
            $me['plan_id'] = $this->courseUser->plan_id;
        }

        if ($this->chapterUser) {
            $me['position'] = $this->chapterUser->position;
        }

        $me['joined'] = $this->joinedChapter ? 1 : 0;
        $me['owned'] = $this->ownedChapter ? 1 : 0;

        if ($user->id > 0) {

            $chapterVoteRepo = new ChapterVoteRepo();

            $chapterVote = $chapterVoteRepo->findChapterVote($chapter->id, $user->id);

            if ($chapterVote) {
                $me['agreed'] = $chapterVote->type == ChapterVoteModel::TYPE_AGREE ? 1 : 0;
                $me['opposed'] = $chapterVote->type == ChapterVoteModel::TYPE_OPPOSE ? 1 : 0;
            }
        }

        $result['me'] = $me;

        return $result;
    }

    protected function handleCourse(CourseModel $course)
    {
        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
            'market_price' => $course->market_price,
            'vip_price' => $course->vip_price,
            'model' => $course->model,
            'level' => $course->level,
            'user_count' => $course->user_count,
            'lesson_count' => $course->lesson_count,
        ];
    }

    protected function formatChapter(ChapterModel $chapter)
    {
        $item = [];

        switch ($this->course->model) {
            case CourseModel::MODEL_VOD:
                $item = $this->formatChapterVod($chapter);
                break;
            case CourseModel::MODEL_LIVE:
                $item = $this->formatChapterLive($chapter);
                break;
            case CourseModel::MODEL_READ:
                $item = $this->formatChapterRead($chapter);
                break;
        }

        return $item;
    }

    protected function formatChapterVod(ChapterModel $chapter)
    {
        $service = new ChapterVodService();

        $playUrls = $service->getPlayUrls($chapter->id);

        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'summary' => $chapter->summary,
            'model' => $chapter->model,
            'play_urls' => $playUrls,
            'user_count' => $chapter->user_count,
            'agree_count' => $chapter->agree_count,
            'oppose_count' => $chapter->oppose_count,
            'comment_count' => $chapter->comment_count,
        ];
    }

    protected function formatChapterLive(ChapterModel $chapter)
    {
        $service = new LiveService();

        $streamName = $this->getLiveStreamName($chapter->id);

        $chapterRepo = new ChapterRepo();

        $live = $chapterRepo->findChapterLive($chapter->id);

        $playUrls = [];

        if ($live->start_time - time() > 1800) {
            $status = 'pending';
        } elseif (time() - $live->end_time > 1800) {
            $status = 'finished';
        } else {
            $status = $service->getStreamState($streamName);
        }

        if ($status == 'active') {
            $playUrls = $service->getPullUrls($streamName);
        }

        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'summary' => $chapter->summary,
            'model' => $chapter->model,
            'status' => $status,
            'start_time' => $live->start_time,
            'end_time' => $live->end_time,
            'play_urls' => $playUrls,
            'user_count' => $chapter->user_count,
            'agree_count' => $chapter->agree_count,
            'oppose_count' => $chapter->oppose_count,
            'comment_count' => $chapter->comment_count,
        ];
    }

    protected function formatChapterRead(ChapterModel $chapter)
    {
        $chapterRepo = new ChapterRepo();

        $read = $chapterRepo->findChapterRead($chapter->id);

        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'summary' => $chapter->summary,
            'model' => $chapter->model,
            'content' => $read->content,
            'user_count' => $chapter->user_count,
            'agree_count' => $chapter->agree_count,
            'oppose_count' => $chapter->oppose_count,
            'comment_count' => $chapter->comment_count,
        ];
    }

    protected function handleCourseUser(CourseModel $course, UserModel $user)
    {
        if ($user->id == 0) return;

        if ($this->joinedCourse) return;

        if (!$this->ownedCourse) return;

        $courseUser = new CourseUserModel();

        $courseUser->course_id = $course->id;
        $courseUser->user_id = $user->id;
        $courseUser->source_type = CourseUserModel::SOURCE_FREE;
        $courseUser->role_type = CourseUserModel::ROLE_STUDENT;
        $courseUser->expiry_time = strtotime('+3 years');

        $courseUser->create();

        $this->courseUser = $courseUser;

        $this->joinedCourse = true;

        $this->incrCourseUserCount($course);
    }

    protected function handleChapterUser(ChapterModel $chapter, UserModel $user)
    {
        if ($user->id == 0) return;

        if (!$this->joinedCourse) return;

        if (!$this->ownedChapter) return;

        if ($this->joinedChapter) return;

        $chapterUser = new ChapterUserModel();

        $chapterUser->plan_id = $this->courseUser->plan_id;
        $chapterUser->course_id = $chapter->course_id;
        $chapterUser->chapter_id = $chapter->id;
        $chapterUser->user_id = $user->id;

        $chapterUser->create();

        $this->chapterUser = $chapterUser;

        $this->joinedChapter = true;

        $this->incrChapterUserCount($chapter);
    }

    protected function getVodPosition(ChapterModel $chapter, UserModel $user)
    {

    }

    protected function getLiveStreamName($id)
    {
        return "chapter_{$id}";
    }

    protected function incrCourseUserCount(CourseModel $course)
    {
        $this->eventsManager->fire('courseCounter:incrUserCount', $this, $course);
    }

    protected function incrChapterUserCount(ChapterModel $chapter)
    {
        $this->eventsManager->fire('chapterCounter:incrUserCount', $this, $chapter);
    }

}
