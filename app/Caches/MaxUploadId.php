<?php

namespace App\Caches;

use App\Models\Upload as UploadModel;

class MaxUploadId extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_upload_id';
    }

    public function getContent($id = null)
    {
        $upload = UploadModel::findFirst(['order' => 'id DESC']);

        return $upload->id ?? 0;
    }

}
