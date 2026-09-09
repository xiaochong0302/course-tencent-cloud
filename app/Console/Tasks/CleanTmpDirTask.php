<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

class CleanTmpDirTask extends Task
{

    public function mainAction(): void
    {
        $excludes = ['.', '..', '.gitignore', 'sitemap.xml'];

        $dir = tmp_path();

        $files = scandir($dir);

        foreach ($files as $file) {
            if (!in_array($file, $excludes)) {
                $filepath = tmp_path($file);
                $deleted = unlink($filepath);
                if ($deleted) {
                    $this->successPrint("remove {$file} success");
                } else {
                    $this->errorPrint("remove {$file} failed");
                }
            }
        }
    }

}
