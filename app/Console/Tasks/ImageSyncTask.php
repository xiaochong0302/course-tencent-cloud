<?php

namespace App\Console\Tasks;

use App\Models\Course;
use App\Services\Storage;
use Phalcon\Cli\Task;
use Phalcon\Text;

class ImageSyncTask extends Task
{

    public function mainAction()
    {
        $courses = Course::query()
            ->where('id > 1155')
            ->execute();

        $storage = new Storage();

        foreach ($courses as $course) {

            $cover = $course->cover;

            if (Text::startsWith($cover, '//')) {
                $cover = 'http:' . $cover;
            }

            $url = str_replace('-360-202', '', $cover);

            $fileName = parse_url($url, PHP_URL_PATH);
            $filePath = tmp_path() . $fileName;
            $content = file_get_contents($url);

            if ($content === false) {
                echo "get course {$course->id} cover failed" . PHP_EOL;
                return;
            }

            $put = file_put_contents($filePath, $content);

            if ($put === false) {
                echo "put course {$course->id} cover failed" . PHP_EOL;
                return;
            }

            $keyName = $this->getKeyName($filePath);
            $remoteUrl = $storage->putFile($keyName, $filePath);

            if ($remoteUrl) {
                $course->cover = $keyName;
                $course->update();
                echo "upload cover of course {$course->id} success" . PHP_EOL;
            } else {
                echo "upload cover of course {$course->id} failed" . PHP_EOL;
            }
        }
    }

    protected function getKeyName($filePath)
    {
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        return '/img/cover/' . date('YmdHis') . rand(1000, 9999) . '.' . $ext;
    }

}
