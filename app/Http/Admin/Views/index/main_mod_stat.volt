<div class="layui-card layui-text kg-stats">
    <div class="layui-card-header">审核队列</div>
    <div class="layui-card-body">
        <div class="layui-row layui-col-space10">
            <div class="layui-col-md3">
                <div class="kg-stat-card">
                    <div class="name">文章</div>
                    <div class="count">
                        <a href="{{ url({'for':'admin.mod.articles'}) }}">{{ mod_stat.article_count }}</a>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="kg-stat-card">
                    <div class="name">提问</div>
                    <div class="count">
                        <a href="javascript:">0</a>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="kg-stat-card">
                    <div class="name">回答</div>
                    <div class="count">
                        <a href="javascript:">0</a>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="kg-stat-card">
                    <div class="name">评论</div>
                    <div class="count">
                        <a href="javascript:">0</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>