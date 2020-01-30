<?php

namespace App\Console\Tasks;

use App\Models\Slide as SlideModel;
use Phalcon\Cli\Task;

class CloseSlideTask extends Task
{

    public function mainAction()
    {
        $slides = $this->findSlides();

        if ($slides->count() == 0) {
            return;
        }

        foreach ($slides as $slide) {
            $slide->published = 0;
            $slide->update();
        }
    }

    /**
     * 查找待关闭轮播
     *
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    protected function findSlides()
    {
        $Slides = SlideModel::query()
            ->where('published = 1')
            ->andWhere('end_time < :end_time:', ['end_time' => time()])
            ->execute();

        return $Slides;
    }

}
