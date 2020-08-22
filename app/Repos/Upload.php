<?php

namespace App\Repos;

use App\Models\Upload as UploadModel;
use Phalcon\Mvc\Model;

class Upload extends Repository
{

    /**
     * @param int $id
     * @return UploadModel|Model|bool
     */
    public function findById($id)
    {
        return UploadModel::findFirst($id);
    }

    /**
     * @param string $md5
     * @return UploadModel|Model|bool
     */
    public function findByMd5($md5)
    {
        return UploadModel::findFirst([
            'conditions' => 'md5 = :md5:',
            'bind' => ['md5' => $md5],
        ]);
    }

}
