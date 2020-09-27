<div class="layui-card kg-stats">
    <div class="layui-card-header">今日统计</div>
    <div class="layui-card-body">
        <div class="layui-row layui-col-space10">
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="name">用户注册</div>
                    <div class="count">{{ today_stat.user_count }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="name">成交订单</div>
                    <div class="count">{{ today_stat.order_count }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="name">销售金额</div>
                    <div class="count">{{ today_stat.sale_amount }}</div>
                </div>
            </div>
        </div>
    </div>
</div>