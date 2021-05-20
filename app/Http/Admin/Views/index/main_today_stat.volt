<div class="layui-card kg-stats">
    <div class="layui-card-header">今日统计</div>
    <div class="layui-card-body">
        <div class="layui-row layui-col-space10">
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="name">用户注册</div>
                    <div class="count">{{ today_stat.register_count }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="name">积分兑换</div>
                    <div class="count">{{ today_stat.point_redeem_count }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="name">成交订单</div>
                    <div class="count">{{ today_stat.sale_count }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="name">成功退款</div>
                    <div class="count">{{ today_stat.refund_count }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="name">销售金额</div>
                    <div class="count">{{ '￥%0.2f'|format(today_stat.sale_amount) }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="name">退款金额</div>
                    <div class="count">{{ '￥%0.2f'|format(today_stat.refund_amount) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>