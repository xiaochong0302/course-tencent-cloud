<?php

namespace App\Console\Tasks;

use App\Models\User;
use App\Services\Storage;
use Phalcon\Cli\Task;
use Phalcon\Text;

class AvatarSyncTask extends Task
{

    public function mainAction()
    {
        $limit = 250;

        foreach (range(1, 2) as $page) {

            $offset = ($page - 1) * $limit;

            $users = User::query()
                ->where('edu_role = 1')
                ->limit($limit, $offset)
                ->execute();

            if ($users->count() > 0) {
                $this->handleUsers($users);
            }
        }
    }

    protected function handleUsers($users)
    {
        $storage = new Storage();

        foreach ($users as $user) {

            $avatar = $user->avatar;

            if (!$avatar) {
                continue;
            }

            if (Text::startsWith($avatar, '/img/avatar')) {
                continue;
            }

            if (Text::startsWith($avatar, '//')) {
                $avatar = 'http:' . $avatar;
            }

            $url = str_replace(['-40-40', '-80-80', '-140-140', '-160-160'], '-200-200', $avatar);

            $fileName = parse_url($url, PHP_URL_PATH);
            $filePath = tmp_path('avatar') . $fileName;

            $content = file_get_contents($url);

            if ($content === false) {
                echo "get user {$user->id} avatar failed" . PHP_EOL;
                continue;
            }

            $put = file_put_contents($filePath, $content);

            if ($put === false) {
                echo "put user {$user->id} cover failed" . PHP_EOL;
                continue;
            }

            $keyName = $this->getKeyName($filePath);
            $remoteUrl = $storage->putFile($keyName, $filePath);

            if ($remoteUrl) {
                $user->avatar = $keyName;
                $user->deleted = 2;
                $user->update();
                echo "upload avatar of user {$user->id} success" . PHP_EOL;
            } else {
                echo "upload avatar of user {$user->id} failed" . PHP_EOL;
            }
        }
    }

    protected function getKeyName($filePath)
    {
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        return '/img/avatar/' . date('YmdHis') . rand(1000, 9999) . '.' . $ext;
    }

}
