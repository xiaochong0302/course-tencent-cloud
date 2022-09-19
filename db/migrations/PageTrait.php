<?php
/**
 * @copyright Copyright (c) 2022 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

trait PageTrait
{

    protected function insertPages(array $rows)
    {
        foreach ($rows as $key => $row) {
            $exists = $this->pageExits($row['alias']);
            if ($exists) unset($rows[$key]);
        }

        if (count($rows) == 0) return;

        $this->table('kg_page')->insert($rows)->save();
    }

    protected function pageExits($alias)
    {
        $row = $this->getQueryBuilder()
            ->select('*')
            ->from('kg_page')
            ->where(['alias' => $alias])
            ->execute()->fetch();

        return $row ? true : false;
    }

}