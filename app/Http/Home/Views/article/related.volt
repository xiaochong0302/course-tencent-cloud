{% if articles %}
    <div class="layui-card">
        <div class="layui-card-header">相关文章</div>
        <div class="layui-card-body">
            <div class="sidebar-article-list">
                {% for article in articles %}
                    {% set article_url = url({'for':'home.article.show','id':article.id}) %}
                    <div class="title">
                        <a href="{{ article_url }}" target="_blank">{{ article.title }}</a>
                    </div>
                    <div class="meta">
                        <span class="view">{{ article.view_count }} 浏览</span>
                        <span class="like">{{ article.like_count }} 点赞</span>
                        <span class="comment">{{ article.comment_count }} 评论</span>
                    </div>
                {% endfor %}
            </div>
        </div>
    </div>
{% endif %}