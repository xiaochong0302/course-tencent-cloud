<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Answer as AnswerModel;
use App\Models\Comment as CommentModel;
use App\Models\Question as QuestionModel;
use App\Models\QuestionFavorite as QuestionFavoriteModel;
use App\Models\QuestionLike as QuestionLikeModel;
use App\Models\QuestionTag as QuestionTagModel;
use App\Models\Tag as TagModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Question extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(QuestionModel::class);

        $builder->where('1 = 1');

        if (!empty($where['tag_id'])) {
            $where['id'] = $this->getTagQuestionIds($where['tag_id']);
        }

        if (!empty($where['id'])) {
            if (is_array($where['id'])) {
                $builder->inWhere('id', $where['id']);
            } else {
                $builder->andWhere('id = :id:', ['id' => $where['id']]);
            }
        }

        if (!empty($where['owner_id'])) {
            $builder->andWhere('owner_id = :owner_id:', ['owner_id' => $where['owner_id']]);
        }

        if (!empty($where['title'])) {
            $builder->andWhere('title LIKE :title:', ['title' => "%{$where['title']}%"]);
        }

        if (isset($where['anonymous'])) {
            $builder->andWhere('anonymous = :anonymous:', ['anonymous' => $where['anonymous']]);
        }

        if (isset($where['closed'])) {
            $builder->andWhere('closed = :closed:', ['closed' => $where['closed']]);
        }

        if (isset($where['solved'])) {
            $builder->andWhere('solved = :solved:', ['solved' => $where['solved']]);
        }

        if (isset($where['published'])) {
            $builder->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        if ($sort == 'unanswered') {
            $builder->andWhere('answer_count = 0');
        }

        if ($sort == 'reported') {
            $builder->andWhere('report_count > 0');
        }

        switch ($sort) {
            case 'active':
                $orderBy = 'last_reply_time DESC';
                break;
            case 'score':
                $orderBy = 'score DESC';
                break;
            default:
                $orderBy = 'id DESC';
                break;
        }

        $builder->orderBy($orderBy);

        $pager = new PagerQueryBuilder([
            'builder' => $builder,
            'page' => $page,
            'limit' => $limit,
        ]);

        return $pager->paginate();
    }

    /**
     * @param int $id
     * @return QuestionModel|Model|bool
     */
    public function findById($id)
    {
        return QuestionModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|QuestionModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return QuestionModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $questionId
     * @return ResultsetInterface|Resultset|TagModel[]
     */
    public function findTags($questionId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('t.*')
            ->addFrom(TagModel::class, 't')
            ->join(QuestionTagModel::class, 't.id = qt.tag_id', 'qt')
            ->where('qt.question_id = :question_id:', ['question_id' => $questionId])
            ->andWhere('t.published = 1')
            ->getQuery()->execute();
    }

    /**
     * @param int $questionId
     * @param int $userId
     * @return ResultsetInterface|Resultset|AnswerModel[]
     */
    public function findUserAnswers($questionId, $userId)
    {
        return AnswerModel::query()
            ->where('question_id = :question_id:', ['question_id' => $questionId])
            ->andWhere('owner_id = :owner_id:', ['owner_id' => $userId])
            ->execute();
    }

    public function countQuestions()
    {
        return (int)QuestionModel::count([
            'conditions' => 'published = :published: AND deleted = 0',
            'bind' => ['published' => QuestionModel::PUBLISH_APPROVED],
        ]);
    }

    public function countAnswers($questionId)
    {
        return (int)AnswerModel::count([
            'conditions' => 'question_id = ?1 AND published = ?2 AND deleted = 0',
            'bind' => [1 => $questionId, 2 => AnswerModel::PUBLISH_APPROVED],
        ]);
    }

    public function countComments($questionId)
    {
        return (int)CommentModel::count([
            'conditions' => 'item_id = ?1 AND item_type = ?2 AND published = ?3 AND deleted = 0',
            'bind' => [1 => $questionId, 2 => CommentModel::ITEM_QUESTION, 3 => CommentModel::PUBLISH_APPROVED],
        ]);
    }

    public function countFavorites($questionId)
    {
        return (int)QuestionFavoriteModel::count([
            'conditions' => 'question_id = :question_id: AND deleted = 0',
            'bind' => ['question_id' => $questionId],
        ]);
    }

    public function countLikes($questionId)
    {
        return (int)QuestionLikeModel::count([
            'conditions' => 'question_id = :question_id: AND deleted = 0',
            'bind' => ['question_id' => $questionId],
        ]);
    }

    protected function getTagQuestionIds($tagId)
    {
        $tagIds = is_array($tagId) ? $tagId : [$tagId];

        $repo = new QuestionTag();

        $rows = $repo->findByTagIds($tagIds);

        $result = [];

        if ($rows->count() > 0) {
            $result = kg_array_column($rows->toArray(), 'question_id');
        }

        return $result;
    }

}
