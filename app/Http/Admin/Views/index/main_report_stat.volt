<div class="layui-card layui-text kg-stats">
    <div class="layui-card-header">举报队列</div>
    <div class="layui-card-body">
        <div class="layui-row layui-col-space10">
            <div class="layui-col-md2">
                <div class="kg-stat-card">
                    <div class="name">文章</div>
                    <div class="count">
                        <a href="{{ url({'for':'admin.report.articles'}) }}">{{ report_stat.article_count }}</a>
                    </div>
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="kg-stat-card">
                    <div class="name">提问</div>
                    <div class="count">
                        <a href="{{ url({'for':'admin.report.questions'}) }}">{{ report_stat.question_count }}</a>
                    </div>
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="kg-stat-card">
                    <div class="name">回答</div>
                    <div class="count">
                        <a href="{{ url({'for':'admin.report.answers'}) }}">{{ report_stat.answer_count }}</a>
                    </div>
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="kg-stat-card">
                    <div class="name">评论</div>
                    <div class="count">
                        <a href="{{ url({'for':'admin.report.comments'}) }}">{{ report_stat.comment_count }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>