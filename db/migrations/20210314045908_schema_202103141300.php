<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Db\Adapter\MysqlAdapter;

class Schema202103141300 extends Phinx\Migration\AbstractMigration
{

    public function up()
    {
        $this->table('kg_flash_sale', [
            'id' => false,
            'primary_key' => ['id'],
            'engine' => 'InnoDB',
            'encoding' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '',
            'row_format' => 'DYNAMIC',
        ])
            ->addColumn('id', 'integer', [
                'null' => false,
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'identity' => 'enable',
                'comment' => '主键编号',
            ])
            ->addColumn('item_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '商品编号',
                'after' => 'id',
            ])
            ->addColumn('item_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '商品类型',
                'after' => 'item_id',
            ])
            ->addColumn('item_info', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '商品信息',
                'after' => 'item_type',
            ])
            ->addColumn('start_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '开始时间',
                'after' => 'item_info',
            ])
            ->addColumn('end_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '结束时间',
                'after' => 'start_time',
            ])
            ->addColumn('schedules', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 255,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '抢购场次',
                'after' => 'end_time',
            ])
            ->addColumn('price', 'decimal', [
                'null' => false,
                'default' => '0.00',
                'precision' => '10',
                'scale' => '2',
                'comment' => '抢购价格',
                'after' => 'schedules',
            ])
            ->addColumn('stock', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '抢购库存',
                'after' => 'price',
            ])
            ->addColumn('published', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '发布标识',
                'after' => 'stock',
            ])
            ->addColumn('deleted', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '删除标识',
                'after' => 'published',
            ])
            ->addColumn('create_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '创建时间',
                'after' => 'deleted',
            ])
            ->addColumn('update_time', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '更新时间',
                'after' => 'create_time',
            ])
            ->addIndex(['end_time'], [
                'name' => 'end_time',
                'unique' => false,
            ])
            ->addIndex(['start_time'], [
                'name' => 'start_time',
                'unique' => false,
            ])
            ->create();

        $this->table('kg_slide')
            ->addColumn('target_attrs', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '目标属性',
                'after' => 'target',
            ])
            ->save();

        $this->table('kg_package')
            ->addColumn('cover', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '封面',
                'after' => 'title',
            ])
            ->save();

        $this->table('kg_vip')
            ->addColumn('cover', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 100,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '封面',
                'after' => 'title',
            ])
            ->save();

        $this->table('kg_order')
            ->addColumn('promotion_id', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '促销编号',
                'after' => 'item_info',
            ])
            ->addColumn('promotion_type', 'integer', [
                'null' => false,
                'default' => '0',
                'limit' => MysqlAdapter::INT_REGULAR,
                'signed' => false,
                'comment' => '促销类型',
                'after' => 'promotion_id',
            ])
            ->addColumn('promotion_info', 'string', [
                'null' => false,
                'default' => '',
                'limit' => 1000,
                'collation' => 'utf8mb4_general_ci',
                'encoding' => 'utf8mb4',
                'comment' => '促销信息',
                'after' => 'promotion_type',
            ])
            ->addIndex(['create_time'], [
                'name' => 'create_time',
                'unique' => false,
            ])
            ->save();

        $this->handleFlashSaleNav();

        $this->handleSlideTargetAttrs();

        $this->handlePackageCover();

        $this->handleVipCover();
    }

    public function down()
    {
        $this->table('kg_flash_sale')
            ->drop()->save();

        $this->table('kg_slide')
            ->removeColumn('target_attrs')
            ->save();

        $this->table('kg_package')
            ->removeColumn('cover')
            ->save();

        $this->table('kg_vip')
            ->removeColumn('cover')
            ->save();

        $this->table('kg_order')
            ->removeColumn('promotion_id')
            ->removeColumn('promotion_type')
            ->removeColumn('promotion_info')
            ->save();
    }

    protected function handleFlashSaleNav()
    {
        $data = [
            'parent_id' => 0,
            'level' => 1,
            'name' => '秒杀',
            'target' => '_self',
            'url' => '/flash/sale',
            'position' => 1,
            'priority' => 100,
            'published' => 1,
            'create_time' => time(),
        ];

        $this->table('kg_nav')
            ->insert($data)
            ->save();

        $nav = $this->getQueryBuilder()
            ->select('*')
            ->from('kg_nav')
            ->orderDesc('id')
            ->execute()->fetch('assoc');

        $this->getQueryBuilder()
            ->update('kg_nav')
            ->set('path', ",{$nav['id']},")
            ->where(['id' => $nav['id']])
            ->execute();
    }

    protected function handleSlideTargetAttrs()
    {
        $slides = $this->getQueryBuilder()
            ->select('*')
            ->from('kg_slide')
            ->execute();

        if ($slides->count() == 0) return;

        $targetAttrs = [];

        foreach ($slides as $slide) {
            if ($slide['target'] == 1) {
                $course = $this->findCourseById($slide['content']);
                if ($course) {
                    $targetAttrs = [
                        'course' => ['id' => $course['id'], 'title' => $course['title']]
                    ];
                }
            } elseif ($slide['target'] == 2) {
                $page = $this->findPageById($slide['content']);
                if ($page) {
                    $targetAttrs = [
                        'page' => ['id' => $page['id'], 'title' => $page['title']]
                    ];
                }
            } elseif ($slide['target'] == 3) {
                $targetAttrs = [
                    'link' => ['url' => $slide['content']]
                ];
            }

            $this->updateTargetAttrs($slide['id'], $targetAttrs);
        }
    }

    protected function handlePackageCover()
    {
        $cover = '/img/default/package_cover.png';

        $this->getQueryBuilder()
            ->update('kg_package')
            ->set('cover', $cover)
            ->execute();
    }

    protected function handleVipCover()
    {
        $cover = '/img/default/vip_cover.png';

        $this->getQueryBuilder()
            ->update('kg_vip')
            ->set('cover', $cover)
            ->execute();
    }

    protected function findCourseById($id)
    {
        return $this->getQueryBuilder()
            ->select('*')
            ->from('kg_course')
            ->where(['id' => $id])
            ->execute()->fetch('assoc');
    }

    protected function findPageById($id)
    {
        return $this->getQueryBuilder()
            ->select('*')
            ->from('kg_page')
            ->where(['id' => $id])
            ->execute()->fetch('assoc');
    }

    protected function updateTargetAttrs($id, $targetAttrs)
    {
        $targetAttrs = json_encode($targetAttrs, JSON_UNESCAPED_UNICODE);

        $this->getQueryBuilder()
            ->update('kg_slide')
            ->set('target_attrs', $targetAttrs)
            ->where(['id' => $id])
            ->execute();
    }

}
