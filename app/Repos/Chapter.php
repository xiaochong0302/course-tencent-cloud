<?php

namespace App\Repos;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterLive as ChapterLiveModel;
use App\Models\ChapterRead as ChapterReadModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\ChapterVod as ChapterVodModel;
use App\Models\ChapterVote as ChapterVoteModel;
use App\Models\Comment as CommentModel;
use App\Models\CommentVote as CommentVoteModel;
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

        if (isset($where['published'])) {
            $query->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $query->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        return $query->execute();
    }

    /**
     * @param int $id
     * @return ChapterModel|Model|bool
     */
    public function findById($id)
    {
        return ChapterModel::findFirst($id);
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
     * @param string $fileId
     * @return ChapterModel|Model|bool
     */
    public function findByFileId($fileId)
    {
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
     * @param int $userId
     * @return ResultsetInterface|Resultset|CommentVoteModel[]
     */
    public function findUserCommentVotes($chapterId, $userId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('cv.*')
            ->addFrom(CommentModel::class, 'c')
            ->join(CommentVoteModel::class, 'c.id = cv.comment_id', 'cv')
            ->where('c.chapter_id = :chapter_id:', ['chapter_id' => $chapterId])
            ->andWhere('cv.user_id = :user_id:', ['user_id' => $userId])
            ->getQuery()->execute();
    }

    public function maxChapterPriority($courseId)
    {
        return ChapterModel::maximum([
            'column' => 'priority',
            'conditions' => 'course_id = :course_id: AND parent_id = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    public function maxLessonPriority($chapterId)
    {
        return ChapterModel::maximum([
            'column' => 'priority',
            'conditions' => 'parent_id = :parent_id:',
            'bind' => ['parent_id' => $chapterId],
        ]);
    }

    public function countLessons($chapterId)
    {
        return ChapterModel::count([
            'conditions' => 'parent_id = :chapter_id: AND deleted = 0',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    public function countUsers($chapterId)
    {
        return ChapterUserModel::count([
            'conditions' => 'chapter_id = :chapter_id: AND deleted = 0',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    public function countComments($chapterId)
    {
        return CommentModel::count([
            'conditions' => 'chapter_id = :chapter_id: AND deleted = 0',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    public function countAgrees($chapterId)
    {
        $type = ChapterVoteModel::TYPE_AGREE;

        return ChapterVoteModel::count([
            'conditions' => 'chapter_id = :chapter_id: AND type = :type: AND deleted = 0',
            'bind' => ['chapter_id' => $chapterId, 'type' => $type],
        ]);
    }

    public function countOpposes($chapterId)
    {
        $type = ChapterVoteModel::TYPE_OPPOSE;

        return ChapterVoteModel::count([
            'conditions' => 'chapter_id = :chapter_id: AND type = :type: AND deleted = 0',
            'bind' => ['chapter_id' => $chapterId, 'type' => $type],
        ]);
    }

    public function countUserComments($chapterId, $userId)
    {
        return CommentModel::count([
            'conditions' => 'chapter_id = :chapter_id: AND user_id = :user_id:',
            'bind' => ['chapter_id' => $chapterId, 'user_id' => $userId],
        ]);
    }

}
