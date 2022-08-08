<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Repos\Answer as AnswerRepo;
use App\Repos\Article as ArticleRepo;
use App\Repos\Comment as CommentRepo;
use App\Repos\Consult as ConsultRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\Package as PackageRepo;
use App\Repos\Question as QuestionRepo;
use App\Repos\Review as ReviewRepo;
use App\Repos\Topic as TopicRepo;
use App\Repos\User as UserRepo;

class SiteGlobalStat extends Cache
{

    protected $lifetime = 15 * 60;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'site_global_stat';
    }

    public function getContent($id = null)
    {
        $courseRepo = new CourseRepo();
        $articleRepo = new ArticleRepo();
        $questionRepo = new QuestionRepo();
        $answerRepo = new AnswerRepo();
        $commentRepo = new CommentRepo();
        $consultRepo = new ConsultRepo();
        $packageRepo = new PackageRepo();
        $reviewRepo = new ReviewRepo();
        $topicRepo = new TopicRepo();
        $userRepo = new UserRepo();

        return [
            'course_count' => $courseRepo->countCourses(),
            'article_count' => $articleRepo->countArticles(),
            'question_count' => $questionRepo->countQuestions(),
            'answer_count' => $answerRepo->countAnswers(),
            'comment_count' => $commentRepo->countComments(),
            'consult_count' => $consultRepo->countConsults(),
            'vip_count' => $userRepo->countVipUsers(),
            'package_count' => $packageRepo->countPackages(),
            'review_count' => $reviewRepo->countReviews(),
            'topic_count' => $topicRepo->countTopics(),
            'user_count' => $userRepo->countUsers(),
        ];
    }

}
