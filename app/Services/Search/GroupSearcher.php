<?php

namespace App\Services\Search;

class GroupSearcher extends Searcher
{

    public function __construct()
    {
        $this->xs = $this->getXS();
    }

    public function getXS()
    {
        $filename = config_path('xs.group.ini');

        return new \XS($filename);
    }

    public function getHighlightFields()
    {
        return ['name', 'about'];
    }

}
