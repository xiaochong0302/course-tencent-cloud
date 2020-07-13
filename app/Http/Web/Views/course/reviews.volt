{%- macro star_info(rating) %}
    {% set stars = [1,2,3,4,5] %}
    {% for val in stars if val <= rating %}
        <i class="layui-icon layui-icon-star-fill"></i>
    {% endfor %}
{%- endmacro %}

{% if pager.total_pages > 0 %}
    <div class="review-list">
        {% for item in pager.items %}
            {% set user_url = url({'for':'web.user.show','id':item.id}) %}
            {% set like_url = url({'for':'web.review.like','id':item.id}) %}
            <div class="review-card clearfix">
                <div class="avatar">
                    <img src="{{ item.user.avatar }}" alt="{{ item.user.name }}">
                </div>
                <div class="info">
                    <div class="rating">{{ star_info(item.rating) }}</div>
                    <div class="user">
                        <a href="{{ user_url }}">{{ item.user.name }}</a>
                    </div>
                    <div class="content">{{ item.content }}</div>
                    <div class="footer">
                        <span class="time">{{ date('Y-m-d H:i',item.create_time) }}</span>
                        <span class="like">
                            <i class="layui-icon layui-icon-praise like-icon" title="点赞" data-url="{{ like_url }}"></i>
                            <em class="like-count">{{ item.like_count }}</em>
                        </span>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}