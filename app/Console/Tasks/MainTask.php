<?php

namespace App\Console\Tasks;

use App\Models\Chapter;

class MainTask extends Task
{

    public function mainAction()
    {
        echo "You are now flying with Phalcon CLI!";
    }

    public function okAction()
    {
        $chapter = Chapter::findFirstById(15224);

        $chapter->duration = 123;

        echo $chapter->duration;
    }

}
