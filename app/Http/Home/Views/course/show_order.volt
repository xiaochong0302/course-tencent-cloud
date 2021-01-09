{% if course.me.owned == 0 and course.market_price > 0 %}
    {% set order_url = url({'for':'home.order.confirm'},{'item_id':course.id,'item_type':1}) %}
    {% set live_model_ok = course.model == 2 and course.attrs.end_date < date('Y-m-d') %}
    {% set other_model_ok = course.model != 2 %}
    {% if live_model_ok or other_model_ok %}
        <div class="sidebar wrap">
            <button class="layui-btn layui-btn-fluid layui-bg-red btn-buy" data-url="{{ order_url }}">立即购买</button>
        </div>
    {% endif %}
{% endif %}

{% if course.market_price == 0 %}
    <div class="sidebar">
        <div class="layui-card">
            <div class="layui-card-header">赞赏支持</div>
            <div class="layui-card-body">
                <div class="sidebar-order">
                    {% for reward in rewards %}
                        {% set item_id = [course.id,reward.id]|join('-') %}
                        {% set order_url = url({'for':'home.order.confirm'},{'item_id':item_id,'item_type':3}) %}
                        <button class="layui-btn layui-btn-xs btn-reward" data-url="{{ order_url }}">{{ reward.title }}</button>
                    {% endfor %}
                </div>
            </div>
        </div>
    </div>
{% endif %}