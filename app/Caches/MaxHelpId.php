<?php

namespace App\Caches;

use App\Models\Help as HelpModel;

class MaxHelpId extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_id:help';
    }

    public function getContent($id = null)
    {
        $help = HelpModel::findFirst(['order' => 'id DESC']);

        return $help->id ?? 0;
    }

}
