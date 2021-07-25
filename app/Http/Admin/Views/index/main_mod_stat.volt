<div class="layui-card layui-text kg-stats">
    <div class="layui-card-header">审核队列</div>
    <div class="layui-card-body">
        <div class="layui-row layui-col-space10">
            <div class="layui-col-md2">
                <div class="kg-stat-card">
                    <div class="name">评价</div>
                    <div class="count">
                        <a href="{{ url({'for':'admin.mod.reviews'}) }}">{{ mod_stat.review_count }}</a>
                    </div>
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="kg-stat-card">
                    <div class="name">咨询</div>
                    <div class="count">
                        <a href="{{ url({'for':'admin.mod.consults'}) }}">{{ mod_stat.consult_count }}</a>
                    </div>
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="kg-stat-card">
                    <div class="name">文章</div>
                    <div class="count">
                        <a href="{{ url({'for':'admin.mod.articles'}) }}">{{ mod_stat.article_count }}</a>
                    </div>
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="kg-stat-card">
                    <div class="name">提问</div>
                    <div class="count">
                        <a href="{{ url({'for':'admin.mod.questions'}) }}">{{ mod_stat.question_count }}</a>
                    </div>
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="kg-stat-card">
                    <div class="name">回答</div>
                    <div class="count">
                        <a href="{{ url({'for':'admin.mod.answers'}) }}">{{ mod_stat.answer_count }}</a>
                    </div>
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="kg-stat-card">
                    <div class="name">评论</div>
                    <div class="count">
                        <a href="{{ url({'for':'admin.mod.comments'}) }}">{{ mod_stat.comment_count }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>