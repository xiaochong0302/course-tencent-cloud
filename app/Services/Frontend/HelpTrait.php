<?php

namespace App\Services\Frontend;

use App\Validators\Help as HelpValidator;

trait HelpTrait
{

    public function checkHelp($id)
    {
        $validator = new HelpValidator();

        return $validator->checkHelp($id);
    }

    public function checkHelpCache($id)
    {
        $validator = new HelpValidator();

        return $validator->checkHelpCache($id);
    }

}
