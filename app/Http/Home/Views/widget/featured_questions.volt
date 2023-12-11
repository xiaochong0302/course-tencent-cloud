{% if questions|length > 0 %}
    <div class="layui-card">
        <div class="layui-card-header">推荐问题</div>
        <div class="layui-card-body">
            <div class="sidebar-question-list">
                {% for question in questions %}
                    {% set question_url = url({'for':'home.question.show','id':question.id}) %}
                    <div class="title layui-elip">
                        <a href="{{ question_url }}" target="_blank">{{ question.title }}</a>
                    </div>
                    <div class="meta">
                        <span class="view">{{ question.view_count }} 浏览</span>
                        <span class="like">{{ question.like_count }} 点赞</span>
                        <span class="comment">{{ question.comment_count }} 评论</span>
                    </div>
                {% endfor %}
            </div>
        </div>
    </div>
{% endif %}