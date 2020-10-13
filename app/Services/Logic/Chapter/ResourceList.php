<?php

namespace App\Services\Logic\Chapter;

use App\Builders\ResourceList as ResourceListBuilder;
use App\Repos\Resource as ResourceRepo;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Service;

class ResourceList extends Service
{

    use ChapterTrait;

    public function handle($id)
    {
        $chapter = $this->checkChapter($id);

        $resourceRepo = new ResourceRepo();

        $resources = $resourceRepo->findByChapterId($chapter->id);

        if ($resources->count() == 0) {
            return [];
        }

        $builder = new ResourceListBuilder();

        $relations = $resources->toArray();

        return $builder->getUploads($relations);
    }

}
