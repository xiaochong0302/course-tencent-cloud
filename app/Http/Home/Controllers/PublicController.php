<?php

namespace App\Http\Home\Controllers;

use App\Models\ContentImage as ContentImageModel;
use App\Services\Storage as StorageService;

class PublicController extends Controller
{

    /**
     * @Get("/content/img/{id}", name="home.content.img")
     */
    public function contentImageAction($id)
    {
        $contentImage = ContentImageModel::findFirstById($id);

        if (!$contentImage) {
            $this->response->setStatusCode(404);
            return $this->response;
        }

        $storageService = new StorageService();

        $location = $storageService->getCiImageUrl($contentImage->path);

        $this->response->redirect($location);
    }

}
