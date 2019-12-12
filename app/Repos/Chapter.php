<?php

namespace App\Repos;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterArticle as ChapterArticleModel;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Models\ChapterVod as ChapterVodModel;

class Chapter extends Repository
{

    /**
     * @param integer $id
     * @return ChapterModel
     */
    public function findById($id)
    {
        $result = ChapterModel::findFirstById($id);

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
     * @param integer $chapterId
     * @return ChapterVodModel
     */
    public function findChapterVod($chapterId)
    {
        $result = ChapterVodModel::findFirstByChapterId($chapterId);

        return $result;
    }

    /**
     * @param integer $chapterId
     * @return ChapterLiveModel
     */
    public function findChapterLive($chapterId)
    {
        $result = ChapterLiveModel::findFirstByChapterId($chapterId);

        return $result;
    }

    /**
     * @param integer $chapterId
     * @return ChapterArticleModel
     */
    public function findChapterArticle($chapterId)
    {
        $result = ChapterArticleModel::findFirstByChapterId($chapterId);

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

        return (int)$result;
    }

}
