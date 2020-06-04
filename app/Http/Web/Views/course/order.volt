{% if course.me.owned == 0 and course.market_price > 0 %}
    <div class="sidebar-order container">
        <div class="order">
            {% set order_url = url({'for':'web.order.confirm'},{'item_id':course.id,'item_type':'course'}) %}
            <a class="layui-btn layui-btn-fluid layui-bg-red" href="{{ order_url }}">立即购买</a>
        </div>
        <div class="consult">
            <a class="layui-btn layui-btn-fluid layui-bg-blue" href="javascript:">课程咨询</a>
        </div>
    </div>
{% endif %}

{% if course.market_price == 0 %}
    <div class="layui-card">
        <div class="layui-card-header">赞赏支持</div>
        <div class="layui-card-body">
            <div class="sidebar-order">
                {% for reward in rewards %}
                    {% set item_id = [course.id,reward.id]|join('-') %}
                    {% set order_url = url({'for':'web.order.confirm'},{'item_id':item_id,'item_type':'reward'}) %}
                    <a class="layui-btn layui-btn-xs reward-btn" href="{{ order_url }}">{{ reward.title }}</a>
                {% endfor %}
            </div>
        </div>
    </div>
{% endif %}