<?php

namespace App\Services\Search;

class CourseSearcher extends Searcher
{

    public function __construct()
    {
        $this->xs = $this->getXS();
    }

    public function getXS()
    {
        $filename = config_path('xs.course.ini');

        return new \XS($filename);
    }

    public function getHighlightFields()
    {
        return ['title', 'summary'];
    }

}
