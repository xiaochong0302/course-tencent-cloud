<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

trait ContentTrait
{

    public function handleContent($content)
    {
        return $this->handleCosImageStyle($content);
    }

    protected function handleCosImageStyle($content)
    {
        $style = '!content_800';

        $pattern = '/src="(.*?)\/img\/content\/(.*?)"/';

        $replacement = 'src="$1/img/content/$2' . $style . '"';

        return preg_replace($pattern, $replacement, $content);
    }

}
