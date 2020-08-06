<?php

namespace App\Repos;

use App\Models\UploadFile as UploadFileModel;
use Phalcon\Mvc\Model;

class UploadFile extends Repository
{

    /**
     * @param int $id
     * @return UploadFileModel|Model|bool
     */
    public function findById($id)
    {
        return UploadFileModel::findFirst($id);
    }

    /**
     * @param string $md5
     * @return UploadFileModel|Model|bool
     */
    public function findByMd5($md5)
    {
        return UploadFileModel::findFirst([
            'conditions' => 'md5 = :md5:',
            'bind' => ['md5' => $md5],
        ]);
    }

}
