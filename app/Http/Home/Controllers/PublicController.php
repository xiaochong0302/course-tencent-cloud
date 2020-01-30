<?php

namespace App\Http\Home\Controllers;

use App\Models\ContentImage as ContentImageModel;
use App\Services\Storage as StorageService;

class PublicController extends \Phalcon\Mvc\Controller
{

    /**
     * @Get("/content/img/{id:[0-9]+}", name="home.content.img")
     */
    public function contentImageAction($id)
    {
        $contentImage = ContentImageModel::findFirst($id);

        if (!$contentImage) {

            $this->response->setStatusCode(404);

            return $this->response;
        }

        $storageService = new StorageService();

        $location = $storageService->getCiImageUrl($contentImage->path);

        $this->response->redirect($location);
    }

    /**
     * @Get("/robot", name="home.robot")
     */
    public function robotAction()
    {

    }

}
