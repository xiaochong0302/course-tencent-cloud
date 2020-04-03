<?php

namespace App\Http\Web\Controllers;

/**
 * @RoutePrefix("/search")
 */
class SearchController extends Controller
{

    /**
     * @Get("/courses", name="web.search.courses")
     */
    public function coursesAction()
    {
        $query = $this->request->getQuery('q');

        $indexer = new \App\Library\Indexer\Course();

        $courses = $indexer->search($query);
        
        echo "total: {$courses['total']}<br>";
        echo "<hr>";

        foreach ($courses['items'] as $course) {
            echo "title:{$course->title}<br>";
            echo "summary:{$course->summary}<br>";
            echo "tags:{$course->tags}<br>";
            echo "<hr>";
        }

        exit;
    }

    /**
     * @Get("/course/update", name="web.search.update_course")
     */
    public function updateCourseAction()
    {
        $indexer = new \App\Library\Indexer\Course();

        $courseRepo = new \App\Repos\Course();

        $course = $courseRepo->findById(1);

        $indexer->updateIndex($course);
        
        echo "update ok";

        exit;
    }

    /**
     * @Get("/course/create", name="web.search.create_course")
     */
    public function createCourseAction()
    {
        $indexer = new \App\Library\Indexer\Course();

        $courseRepo = new \App\Repos\Course();

        $course = $courseRepo->findById(1);

        $indexer->addIndex($course);
        
        echo "create ok";

        exit;
    }

    /**
     * @Get("/course/delete", name="web.search.delete_course")
     */
    public function deleteCourseAction()
    {
        $indexer = new \App\Library\Indexer\Course();

        $courseRepo = new \App\Repos\Course();

        $course = $courseRepo->findById(1);

        $indexer->deleteIndex($course);
        
        echo "delete ok";
        
        exit;
    }

}
