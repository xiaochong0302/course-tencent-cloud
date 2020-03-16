<?php

namespace App\Services\Frontend;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\ChapterVote as ChapterVoteModel;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\ChapterVote as ChapterVoteRepo;
use App\Services\ChapterVod as ChapterVodService;
use App\Services\Live as LiveService;
use WhichBrowser\Parser as BrowserParser;

class Chapter extends Service
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

    public function getChapter($id)
    {
        $chapter = $this->checkChapter($id);

        $this->course = $this->checkCourse($chapter->course_id);

        $this->user = $this->getCurrentUser();

        $this->setCourseUser($this->course, $this->user);

        $this->setChapterUser($chapter, $this->user);

        $this->handleChapterUser($chapter);

        return $this->handleChapter($chapter);
    }

    /**
     * @param ChapterModel $chapter
     * @return array
     */
    protected function handleChapter(ChapterModel $chapter)
    {
        $result = $this->formatChapter($chapter);

        $me = [
            'agreed' => false,
            'opposed' => false,
        ];

        $me['owned'] = $this->ownedChapter;

        if ($this->user->id > 0) {

            $chapterVoteRepo = new ChapterVoteRepo();

            $chapterVote = $chapterVoteRepo->findChapterVote($chapter->id, $this->user->id);

            if ($chapterVote) {
                $me['agreed'] = $chapterVote->type == ChapterVoteModel::TYPE_AGREE;
                $me['opposed'] = $chapterVote->type == ChapterVoteModel::TYPE_OPPOSE;
            }
        }

        $result['me'] = $me;

        return $result;
    }

    /**
     * @param ChapterModel $chapter
     * @return array
     */
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

    /**
     * @param ChapterModel $chapter
     * @return array
     */
    protected function formatChapterVod(ChapterModel $chapter)
    {
        $chapterVodService = new ChapterVodService();

        $playUrls = $chapterVodService->getPlayUrls($chapter->id);

        $course = $this->formatCourse($this->course);

        $item = [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'summary' => $chapter->summary,
            'course' => $course,
            'play_urls' => $playUrls,
            'agree_count' => $chapter->agree_count,
            'oppose_count' => $chapter->oppose_count,
            'comment_count' => $chapter->comment_count,
            'user_count' => $chapter->user_count,
        ];

        return $item;
    }

    /**
     * @param ChapterModel $chapter
     * @return array
     */
    protected function formatChapterLive(ChapterModel $chapter)
    {
        $headers = getallheaders();

        $browserParser = new BrowserParser($headers);

        $liveService = new LiveService();

        $stream = "chapter-{$chapter->id}";

        $format = $browserParser->isType('desktop') ? 'flv' : 'hls';

        $playUrls = $liveService->getPullUrls($stream, $format);

        $course = $this->formatCourse($this->course);

        $chapterRepo = new ChapterRepo();

        $live = $chapterRepo->findChapterLive($chapter->id);

        $item = [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'summary' => $chapter->summary,
            'course' => $course,
            'play_urls' => $playUrls,
            'start_time' => $live->start_time,
            'end_time' => $live->end_time,
            'agree_count' => $chapter->agree_count,
            'oppose_count' => $chapter->oppose_count,
            'comment_count' => $chapter->comment_count,
            'user_count' => $chapter->user_count,
        ];

        return $item;
    }

    /**
     * @param ChapterModel $chapter
     * @return array
     */
    protected function formatChapterRead(ChapterModel $chapter)
    {
        $chapterRepo = new ChapterRepo();

        $read = $chapterRepo->findChapterRead($chapter->id);

        $course = $this->formatCourse($this->course);

        $item = [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'summary' => $chapter->summary,
            'course' => $course,
            'content' => $read->content,
            'agree_count' => $chapter->agree_count,
            'oppose_count' => $chapter->oppose_count,
            'comment_count' => $chapter->comment_count,
            'user_count' => $chapter->user_count,
        ];

        return $item;
    }

    /**
     * @param CourseModel $course
     * @return array
     */
    protected function formatCourse(CourseModel $course)
    {
        $result = [
            'id' => $course->id,
            'title' => $course->title,
        ];

        return $result;
    }

    /**
     * @param ChapterModel $chapter
     */
    protected function handleChapterUser(ChapterModel $chapter)
    {
        if ($this->user->id == 0) return;

        if (!$this->courseUser) return;

        if ($this->ownedChapter && !$this->joinedChapter) {

            $chapterUser = new ChapterUserModel();

            $chapterUser->course_id = $this->course->id;
            $chapterUser->chapter_id = $chapter->id;
            $chapterUser->user_id = $this->user->id;

            $chapterUser->create();

            $chapter->user_count += 1;

            $chapter->update();
        }
    }

}
