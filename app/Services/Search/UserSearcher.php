<?php

namespace App\Services\Search;

class UserSearcher extends Searcher
{

    public function __construct()
    {
        $this->xs = $this->getXS();
    }

    public function getXS()
    {
        $filename = config_path('xs.user.ini');

        return new \XS($filename);
    }

    public function getHighlightFields()
    {
        return ['name', 'about'];
    }

}
