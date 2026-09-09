<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterRead as ChapterReadModel;
use App\Models\ChapterVod as ChapterVodModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;

class Chapter extends Repository
{

    /**
     * @param array $where
     * @return ResultsetInterface|Resultset|ChapterModel[]
     */
    public function findAll(array $where = [])
    {
        $query = ChapterModel::query();

        $query->where('1 = 1');

        if (isset($where['parent_id'])) {
            $query->andWhere('parent_id = :parent_id:', ['parent_id' => $where['parent_id']]);
        }

        if (isset($where['course_id'])) {
            $query->andWhere('course_id = :course_id:', ['course_id' => $where['course_id']]);
        }

        if (isset($where['model'])) {
            $query->andWhere('model = :model:', ['model' => $where['model']]);
        }

        if (isset($where['published'])) {
            $query->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $query->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        $query->orderBy('priority ASC');

        return $query->execute();
    }

    /**
     * @param int $id
     * @return ChapterModel|Row|null
     */
    public function findById(int $id)
    {
        return ChapterModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|ChapterModel[]
     */
    public function findByIds(array $ids, array|string $columns = '*')
    {
        return ChapterModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param string $fileId
     * @return ChapterModel|Row|null
     */
    public function findByFileId(string $fileId)
    {
        /**
         * @var ChapterVodModel $vod
         */
        $vod = ChapterVodModel::findFirst([
            'conditions' => 'file_id = :file_id:',
            'bind' => ['file_id' => $fileId],
        ]);

        if (!$vod) return null;

        return ChapterModel::findFirst($vod->chapter_id);
    }

    /**
     * @param int $chapterId
     * @return ChapterVodModel|Row|null
     */
    public function findChapterVod(int $chapterId)
    {
        return ChapterVodModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    /**
     * @param int $chapterId
     * @return ChapterLiveModel|Row|null
     */
    public function findChapterLive(int $chapterId)
    {
        return ChapterLiveModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    /**
     * @param int $chapterId
     * @return ChapterReadModel|Row|null
     */
    public function findChapterRead(int $chapterId)
    {
        return ChapterReadModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    /**
     * @param int $chapterId
     * @return ChapterOfflineModel|Row|null
     */
    public function findChapterOffline(int $chapterId)
    {
        return ChapterOfflineModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    /**
     * @param int $chapterId
     * @return ChapterDocModel|Row|null
     */
    public function findChapterDoc(int $chapterId)
    {
        return ChapterDocModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    /**
     * @param int $courseId
     * @return int
     */
    public function maxChapterPriority(int $courseId): int
    {
        return (int)ChapterModel::maximum([
            'column' => 'priority',
            'conditions' => 'course_id = :course_id: AND parent_id = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    /**
     * @param int $chapterId
     * @return int
     */
    public function maxLessonPriority(int $chapterId): int
    {
        return (int)ChapterModel::maximum([
            'column' => 'priority',
            'conditions' => 'parent_id = :parent_id:',
            'bind' => ['parent_id' => $chapterId],
        ]);
    }

    /**
     * @param int $chapterId
     * @return int
     */
    public function countLessons(int $chapterId): int
    {
        return (int)ChapterModel::count([
            'conditions' => 'parent_id = :chapter_id: AND deleted = 0',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

}
