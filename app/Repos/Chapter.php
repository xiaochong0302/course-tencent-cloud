<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterLike as ChapterLikeModel;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Models\ChapterOffline as ChapterOfflineModel;
use App\Models\ChapterRead as ChapterReadModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\ChapterVod as ChapterVodModel;
use App\Models\Comment as CommentModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Chapter extends Repository
{

    /**
     * @param array $where
     * @return ResultsetInterface|Resultset|ChapterModel[]
     */
    public function findAll($where = [])
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
     * @return ChapterModel|Model|bool
     */
    public function findById($id)
    {
        return ChapterModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|ChapterModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return ChapterModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $id
     * @return ResultsetInterface|Resultset|ChapterModel[]
     */
    public function findLessons($id)
    {
        return ChapterModel::query()
            ->where('parent_id = :parent_id:', ['parent_id' => $id])
            ->andWhere('deleted = 0')
            ->orderBy('priority ASC')
            ->execute();
    }

    /**
     * @param string $fileId
     * @return ChapterModel|Model|bool
     */
    public function findByFileId($fileId)
    {
        /**
         * @var ChapterVodModel $vod
         */
        $vod = ChapterVodModel::findFirst([
            'conditions' => 'file_id = :file_id:',
            'bind' => ['file_id' => $fileId],
        ]);

        if (!$vod) return false;

        return ChapterModel::findFirst($vod->chapter_id);
    }

    /**
     * @param int $chapterId
     * @return ChapterVodModel|Model|bool
     */
    public function findChapterVod($chapterId)
    {
        return ChapterVodModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    /**
     * @param int $chapterId
     * @return ChapterLiveModel|Model|bool
     */
    public function findChapterLive($chapterId)
    {
        return ChapterLiveModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    /**
     * @param int $chapterId
     * @return ChapterReadModel|Model|bool
     */
    public function findChapterRead($chapterId)
    {
        return ChapterReadModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    /**
     * @param int $chapterId
     * @return ChapterOfflineModel|Model|bool
     */
    public function findChapterOffline($chapterId)
    {
        return ChapterOfflineModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    public function maxChapterPriority($courseId)
    {
        return (int)ChapterModel::maximum([
            'column' => 'priority',
            'conditions' => 'course_id = :course_id: AND parent_id = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    public function maxLessonPriority($chapterId)
    {
        return (int)ChapterModel::maximum([
            'column' => 'priority',
            'conditions' => 'parent_id = :parent_id:',
            'bind' => ['parent_id' => $chapterId],
        ]);
    }

    public function countLessons($chapterId)
    {
        return (int)ChapterModel::count([
            'conditions' => 'parent_id = :chapter_id: AND deleted = 0',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    public function countUsers($chapterId)
    {
        return (int)ChapterUserModel::count([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    public function countLikes($chapterId)
    {
        return (int)ChapterLikeModel::count([
            'conditions' => 'chapter_id = :chapter_id: AND deleted = 0',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    public function countComments($chapterId)
    {
        return (int)CommentModel::count([
            'conditions' => 'item_id = ?1 AND item_type = ?2 AND published = ?3 AND deleted = 0',
            'bind' => [1 => $chapterId, 2 => CommentModel::ITEM_CHAPTER, 3 => CommentModel::PUBLISH_APPROVED],
        ]);
    }

}
