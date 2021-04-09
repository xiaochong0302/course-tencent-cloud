{{ partial('macros/article') }}

{% if pager.total_pages > 0 %}
    <div class="article-list clearfix">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                {% set article_url = url({'for':'home.article.show','id':item.id}) %}
                <div class="layui-col-md3">
                    <div class="course-card">
                        <span class="type layui-badge layui-bg-green">{{ source_type(item.source_type) }}</span>
                        <div class="cover">
                            <a href="{{ article_url }}" target="_blank">
                                <img src="{{ item.cover }}!cover_270" alt="{{ item.title }}" title="{{ item.title }}">
                            </a>
                        </div>
                        <div class="info">
                            <div class="title layui-elip">
                                <a href="{{ article_url }}" title="{{ item.title }}" target="_blank">{{ item.title }}</a>
                            </div>
                            <div class="meta">
                                <span class="view">{{ item.view_count }} 浏览</span>
                                <span class="like">{{ item.like_count }} 点赞</span>
                                <span class="comment">{{ item.comment_count }} 评论</span>
                            </div>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}