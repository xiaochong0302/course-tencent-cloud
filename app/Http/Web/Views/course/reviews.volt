{{ partial('macros/course') }}

{% if pager.total_pages > 0 %}
    <div class="review-list">
        {% for item in pager.items %}
            {% set owner_url = url({'for':'web.user.show','id':item.owner.id}) %}
            {% set like_url = url({'for':'web.review.like','id':item.id}) %}
            <div class="review-card clearfix">
                <div class="avatar">
                    <a href="{{ owner_url }}" title="{{ item.owner.name }}">
                        <img src="{{ item.owner.avatar }}" alt="{{ item.owner.name }}">
                    </a>
                </div>
                <div class="info">
                    <div class="rating">{{ star_info(item.rating) }}</div>
                    <div class="user">
                        <a href="{{ owner_url }}">{{ item.owner.name }}</a>
                    </div>
                    <div class="content">{{ item.content }}</div>
                    <div class="footer">
                        <span class="time">{{ date('Y-m-d',item.create_time) }}</span>
                        <a href="javascript:" class="like" title="点赞" data-url="{{ like_url }}">
                            <i class="layui-icon layui-icon-praise icon-praise"></i>
                            <em class="like-count">{{ item.like_count }}</em>
                        </a>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}