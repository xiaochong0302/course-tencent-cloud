<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Services\Utils\IndexPageCache as IndexPageCacheUtil;
use App\Services\Utils\OpCache as OpCacheUtil;

class Util extends Service
{

    public function handleCache(): void
    {
        $section = $this->request->getPost('section');

        if ($section == 'index_cache') {
            $this->handleIndexCache();
        } elseif ($section == 'op_cache') {
            $this->handleOpCache();
        }
    }

    protected function handleIndexCache(): void
    {
        $items = $this->request->getPost('items');

        $sections = [
            'slide',
            'featured_course',
            'new_course',
            'free_course',
            'vip_course',
        ];

        if (empty($items)) {
            $items = $sections;
        }

        $util = new IndexPageCacheUtil();

        foreach ($sections as $section) {
            if (in_array($section, $items)) {
                $util->rebuild($section);
            }
        }
    }

    protected function handleOpCache(): void
    {
        $scope = $this->request->getPost('scope');

        $service = new OpCacheUtil();

        $service->reset($scope);
    }

}
