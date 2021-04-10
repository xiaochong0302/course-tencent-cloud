<?php

namespace App\Services\Search;

class ArticleSearcher extends Searcher
{

    public function __construct()
    {
        $this->xs = $this->getXS();
    }

    public function getXS()
    {
        $filename = config_path('xs.article.ini');

        return new \XS($filename);
    }

    public function getHighlightFields()
    {
        return ['title', 'summary'];
    }

}
