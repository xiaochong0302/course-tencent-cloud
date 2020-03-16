<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Repos\Help as HelpRepo;

class Help extends Validator
{

    public function checkHelp($id)
    {
        $helpRepo = new HelpRepo();

        $help = $helpRepo->findById($id);

        if (!$help) {
            throw new BadRequestException('help.not_found');
        }

        return $help;
    }

    public function checkTitle($title)
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('help.title_too_short');
        }

        if ($length > 50) {
            throw new BadRequestException('help.title_too_long');
        }

        return $value;
    }

    public function checkContent($content)
    {
        $value = $this->filter->sanitize($content, ['trim']);

        $length = kg_strlen($value);

        if ($length < 10) {
            throw new BadRequestException('help.content_too_short');
        }

        if ($length > 3000) {
            throw new BadRequestException('help.content_too_long');
        }

        return $value;
    }

    public function checkPriority($priority)
    {
        $value = $this->filter->sanitize($priority, ['trim', 'int']);

        if ($value < 1 || $value > 255) {
            throw new BadRequestException('help.invalid_priority');
        }

        return $value;
    }

    public function checkPublishStatus($status)
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('help.invalid_publish_status');
        }

        return $status;
    }

}
