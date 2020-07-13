{% if course.me.owned == 0 and course.market_price > 0 %}
    <div class="sidebar-order wrap">
        <div class="order">
            {% set order_url = url({'for':'web.order.confirm'},{'item_id':course.id,'item_type':'course'}) %}
            <a class="layui-btn layui-btn-fluid layui-bg-red" href="{{ order_url }}">立即购买</a>
        </div>
        <div class="consult">
            <a class="layui-btn layui-btn-fluid layui-bg-blue" href="javascript:">课程咨询</a>
        </div>
    </div>
{% endif %}

{% set rating_url = url({'for':'web.course.rating','id':course.id}) %}

<div class="sidebar-rating wrap">
    <a class="layui-btn layui-btn-fluid layui-bg-green rating-btn" href="javascript:" data-url="{{ rating_url }}">课程评价</a>
</div>

{% if course.market_price == 0 %}
    <div class="layui-card">
        <div class="layui-card-header">赞赏支持</div>
        <div class="layui-card-body">
            <div class="sidebar-order">
                {% for option in reward_options %}
                    {% set item_id = [course.id,option.id]|join('-') %}
                    {% set order_url = url({'for':'web.order.confirm'},{'item_id':item_id,'item_type':'reward'}) %}
                    <a class="layui-btn layui-btn-xs reward-btn" href="{{ order_url }}">{{ option.title }}</a>
                {% endfor %}
            </div>
        </div>
    </div>
{% endif %}