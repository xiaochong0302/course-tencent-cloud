<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Caches\Chapter as ChapterCache;
use App\Caches\MaxChapterId as MaxChapterIdCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Chapter as ChapterModel;
use App\Models\ChapterRead as ChapterReadModel;
use App\Models\ChapterVod as ChapterVodModel;
use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;

class Chapter extends Validator
{

    public function checkChapterCache(int $id): ChapterModel
    {
        $this->checkId($id);

        $chapterCache = new ChapterCache();

        $chapter = $chapterCache->get($id);

        if (!$chapter) {
            throw new BadRequestException('chapter.not_found');
        }

        return $chapter;
    }

    public function checkChapterVod(int $id): ChapterVodModel
    {
        $this->checkId($id);

        $chapterRepo = new ChapterRepo();

        $chapterVod = $chapterRepo->findChapterVod($id);

        if (!$chapterVod) {
            throw new BadRequestException('chapter.vod_not_found');
        }

        return $chapterVod;
    }

    public function checkChapterRead(int $id): ChapterReadModel
    {
        $this->checkId($id);

        $chapterRepo = new ChapterRepo();

        $chapterRead = $chapterRepo->findChapterRead($id);

        if (!$chapterRead) {
            throw new BadRequestException('chapter.read_not_found');
        }

        return $chapterRead;
    }

    public function checkChapter(int $id): ChapterModel
    {
        $this->checkId($id);

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($id);

        if (!$chapter) {
            throw new BadRequestException('chapter.not_found');
        }

        return $chapter;
    }

    public function checkId(int $id): void
    {
        $maxIdCache = new MaxChapterIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException('chapter.not_found');
        }
    }

    public function checkCourse(int $id): CourseModel
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

    public function checkParent(int $id): ChapterModel
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($id);

        if (!$chapter) {
            throw new BadRequestException('chapter.parent_not_found');
        }

        return $chapter;
    }

    public function checkTitle(string $title): string
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('chapter.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('chapter.title_too_long');
        }

        return $value;
    }

    public function checkSummary(string $summary): string
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('chapter.summary_too_long');
        }

        return $value;
    }

    public function checkKeywords(string $keywords): string
    {
        $keywords = $this->filter->sanitize($keywords, ['trim', 'string']);

        $length = kg_strlen($keywords);

        if ($length > 100) {
            throw new BadRequestException('chapter.keyword_too_long');
        }

        return kg_parse_keywords($keywords);
    }

    public function checkModel(int $model): int
    {
        $list = CourseModel::modelTypes();

        if (!array_key_exists($model, $list)) {
            throw new BadRequestException('chapter.invalid_model');
        }

        return $model;
    }

    public function checkPriority(int $priority): int
    {
        if ($priority < 1 || $priority > 255) {
            throw new BadRequestException('chapter.invalid_priority');
        }

        return $priority;
    }

    public function checkFreeStatus(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('chapter.invalid_free_status');
        }

        return $status;
    }

    public function checkPublishStatus(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('chapter.invalid_publish_status');
        }

        return $status;
    }

    public function checkPublishAbility(ChapterModel $chapter): void
    {
        $attrs = $chapter->attrs;

        if ($chapter->model == CourseModel::MODEL_VOD) {
            if ($attrs['duration'] == 0) {
                throw new BadRequestException('chapter.vod_not_ready');
            }
        } elseif ($chapter->model == CourseModel::MODEL_LIVE) {
            if ($attrs['start_time'] == 0) {
                throw new BadRequestException('chapter.live_time_empty');
            }
        } elseif ($chapter->model == CourseModel::MODEL_READ) {
            if ($attrs['word_count'] == 0) {
                throw new BadRequestException('chapter.read_not_ready');
            }
        } elseif ($chapter->model == CourseModel::MODEL_OFFLINE) {
            if ($attrs['start_time'] == 0) {
                throw new BadRequestException('chapter.offline_time_empty');
            }
        } elseif ($chapter->model == CourseModel::MODEL_DOC) {
            if ($attrs['size'] == 0) {
                throw new BadRequestException('chapter.doc_not_ready');
            }
        }
    }

    public function checkDeleteAbility(ChapterModel $chapter): void
    {
        $chapterRepo = new ChapterRepo();

        $chapters = $chapterRepo->findAll([
            'parent_id' => $chapter->id,
            'deleted' => 0,
        ]);

        if ($chapters->count() > 0) {
            throw new BadRequestException('chapter.child_existed');
        }
    }

}
