<?php

namespace App\Repos;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterLike as ChapterLikeModel;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Models\ChapterRead as ChapterReadModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\ChapterVod as ChapterVodModel;
use App\Models\Comment as CommentModel;

class Chapter extends Repository
{

    /**
     * @param int $id
     * @return ChapterModel
     */
    public function findById($id)
    {
        $result = ChapterModel::findFirst($id);

        return $result;
    }

    /**
     * @param string $fileId
     * @return ChapterModel
     */
    public function findByFileId($fileId)
    {
        $result = $this->modelsManager->createBuilder()
            ->columns('c.*')
            ->addFrom(ChapterModel::class, 'c')
            ->join(ChapterVodModel::class, 'c.id = cv.chapter_id', 'cv')
            ->where('cv.file_id = :file_id:', ['file_id' => $fileId])
            ->getQuery()->execute()->getFirst();

        return $result;
    }

    public function findByIds($ids, $columns = '*')
    {
        $result = ChapterModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

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

        if (isset($where['published'])) {
            $query->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $query->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        $result = $query->execute();

        return $result;
    }

    /**
     * @param int $chapterId
     * @return ChapterVodModel
     */
    public function findChapterVod($chapterId)
    {
        $result = ChapterVodModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);

        return $result;
    }

    /**
     * @param int $chapterId
     * @return ChapterLiveModel
     */
    public function findChapterLive($chapterId)
    {
        $result = ChapterLiveModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);

        return $result;
    }

    /**
     * @param int $chapterId
     * @return ChapterReadModel
     */
    public function findChapterRead($chapterId)
    {
        $result = ChapterReadModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);

        return $result;
    }

    public function maxChapterPriority($courseId)
    {
        $result = ChapterModel::maximum([
            'column' => 'priority',
            'conditions' => 'course_id = :course_id: AND parent_id = 0',
            'bind' => ['course_id' => $courseId],
        ]);

        return $result;
    }

    public function maxLessonPriority($chapterId)
    {
        $result = ChapterModel::maximum([
            'column' => 'priority',
            'conditions' => 'parent_id = :parent_id:',
            'bind' => ['parent_id' => $chapterId],
        ]);

        return $result;
    }

    public function countLessons($chapterId)
    {
        $result = ChapterModel::count([
            'conditions' => 'parent_id = :chapter_id: AND deleted = 0',
            'bind' => ['chapter_id' => $chapterId],
        ]);

        return $result;
    }

    public function countUsers($chapterId)
    {
        $count = ChapterUserModel::count([
            'conditions' => 'chapter_id = :chapter_id: AND deleted = 0',
            'bind' => ['chapter_id' => $chapterId],
        ]);

        return $count;
    }

    public function countComments($chapterId)
    {
        $count = CommentModel::count([
            'conditions' => 'chapter_id = :chapter_id: AND deleted = 0',
            'bind' => ['chapter_id' => $chapterId],
        ]);

        return $count;
    }

    public function countLikes($chapterId)
    {
        $count = ChapterLikeModel::count([
            'conditions' => 'chapter_id = :chapter_id: AND deleted = 0',
            'bind' => ['chapter_id' => $chapterId],
        ]);

        return $count;
    }

}
