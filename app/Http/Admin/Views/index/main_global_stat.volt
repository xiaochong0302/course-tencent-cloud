<div class="layui-card kg-stats">
    <div class="layui-card-header">全局统计</div>
    <div class="layui-card-body">
        <div class="layui-row layui-col-space10">
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="name">用户数</div>
                    <div class="count">{{ global_stat.user_count }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="name">会员数</div>
                    <div class="count">{{ global_stat.vip_count }}</div>
                </div>
            </div>
            <div class="layui-col-md4">
                <div class="kg-stat-card">
                    <div class="name">课程数</div>
                    <div class="count">{{ global_stat.course_count }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
