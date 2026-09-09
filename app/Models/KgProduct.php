<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Models;

class KgProduct
{

    /**
     * 物品类型
     */
    const int ITEM_COURSE = 1; // 课程服务
    const int ITEM_PACKAGE = 2; // 课程套餐
    const int ITEM_VIP = 3; // 会员套餐
    const int ITEM_EXAM_PAPER = 4; // 考试服务
    const int ITEM_ARTICLE = 5; // 专栏文章
    const int ITEM_VIRTUAL_RECHARGE = 10; // 虚拟充值
    const int ITEM_WITHDRAW_ACCOUNT_VERIFY = 98; // 提现账户验证
    const int ITEM_PAY_TEST = 99; // 支付测试
    const int ITEM_GOODS = 100; // 实物商品

}
