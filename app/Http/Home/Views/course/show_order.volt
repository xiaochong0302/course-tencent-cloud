{% if course.me.logged == 0 %}
    {% set login_url = url({'for':'home.account.login'}) %}
    <div class="sidebar wrap">
        <a class="layui-btn layui-btn-fluid" href="{{ login_url }}">用户登录</a>
    </div>
{% elseif course.me.allow_order == 1 %}
    {% set order_url = url({'for':'home.order.confirm'},{'item_id':course.id,'item_type':1}) %}
    <div class="sidebar wrap">
        <button class="layui-btn layui-btn-fluid layui-bg-red btn-buy" data-url="{{ order_url }}">立即购买</button>
    </div>
{% endif %}

{% if course.me.allow_reward == 1 %}
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