<?php

namespace App\Caches;

use App\Repos\Consult as ConsultRepo;
use App\Repos\Course as CourseRepo;
use App\Repos\ImGroup as GroupRepo;
use App\Repos\Order as OrderRepo;
use App\Repos\Package as PackageRepo;
use App\Repos\Review as ReviewRepo;
use App\Repos\Topic as TopicRepo;
use App\Repos\User as UserRepo;

class SiteGlobalStat extends Cache
{

    protected $lifetime = 2 * 3600;

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
        $consultRepo = new ConsultRepo();
        $groupRepo = new GroupRepo();
        $orderRepo = new OrderRepo();
        $packageRepo = new PackageRepo();
        $reviewRepo = new ReviewRepo();
        $topicRepo = new TopicRepo();
        $userRepo = new UserRepo();

        return [
            'course_count' => $courseRepo->countCourses(),
            'consult_count' => $consultRepo->countConsults(),
            'group_count' => $groupRepo->countGroups(),
            'order_count' => $orderRepo->countOrders(),
            'package_count' => $packageRepo->countPackages(),
            'review_count' => $reviewRepo->countReviews(),
            'topic_count' => $topicRepo->countTopics(),
            'user_count' => $userRepo->countUsers(),
        ];
    }

}
