{{ partial('macros/article') }}

{% if pager.total_pages > 0 %}
    <div class="article-list">
        {% for item in pager.items %}
            {% set article_url = url({'for':'home.article.show','id':item.id}) %}
            {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
            <div class="article-card">
                <div class="info">
                    <div class="title layui-elip">
                        <a href="{{ article_url }}" target="_blank">{{ item.title }}</a>
                    </div>
                    <div class="summary">{{ item.summary }}</div>
                    <div class="meta">
                        <span class="owner"><a href="{{ owner_url }}">{{ item.owner.name }}</a></span>
                        <span class="time">{{ item.create_time|time_ago }}</span>
                        <span class="view">{{ item.view_count }} 浏览</span>
                        <span class="like">{{ item.like_count }} 点赞</span>
                        <span class="comment">{{ item.comment_count }} 评论</span>
                    </div>
                </div>
                {% if item.cover %}
                    <div class="cover">
                        <a href="{{ article_url }}" target="_blank">
                            <img src="{{ item.cover }}!cover_270" alt="{{ item.title }}">
                        </a>
                    </div>
                {% endif %}
            </div>
        {% endfor %}
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}
