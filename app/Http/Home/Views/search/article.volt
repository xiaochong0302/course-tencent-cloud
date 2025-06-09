{% if pager.total_pages > 0 %}
    <div class="search-article-list">
        {% for item in pager.items %}
            {% set article_url = url({'for':'home.article.show','id':item.id}) %}
            {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
            <div class="search-article-card article-card">
                <div class="info">
                    <div class="title layui-elip">
                        <a href="{{ article_url }}" target="_blank">{{ item.title }}</a>
                    </div>
                    <div class="summary">{{ item.summary }}</div>
                    <div class="meta">
                        <span>作者：<a href="{{ owner_url }}" target="_blank">{{ item.owner.name }}</a></span>
                        <span>浏览：{{ item.view_count }}</span>
                        <span>点赞：{{ item.like_count }}</span>
                        <span>评论：{{ item.comment_count }}</span>
                    </div>
                </div>
                {% if item.cover %}
                    <div class="cover">
                        <a href="{{ article_url }}" target="_blank">
                            <img src="{{ item.cover }}!cover_270" alt="{{ item.title|striptags }}">
                        </a>
                    </div>
                {% endif %}
            </div>
        {% endfor %}
    </div>
{% else %}
    {{ partial('search/empty') }}
{% endif %}
