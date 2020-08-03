{% if pager.total_pages > 0 %}
    <div class="review-list">
        {% for item in pager.items %}
            {% set item.answer = item.answer ? item.answer : '稍安勿燥，请耐心等待回复吧' %}
            {% set owner_url = url({'for':'web.user.show','id':item.id}) %}
            {% set like_url = url({'for':'web.consult.like','id':item.id}) %}
            <div class="review-card clearfix">
                <div class="avatar">
                    <a href="{{ owner_url }}">
                        <img src="{{ item.owner.avatar }}" alt="{{ item.owner.name }}" title="{{ item.owner.name }}">
                    </a>
                </div>
                <div class="info">
                    <div class="title">{{ item.question }}</div>
                    <div class="content">{{ item.answer }}</div>
                    <div class="footer">
                        <span class="time">{{ item.create_time|time_ago }}</span>
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