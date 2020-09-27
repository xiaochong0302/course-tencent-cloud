{%- macro item_info(order) %}
    {% if order.item_type == 1 %}
        {% set course = order.item_info['course'] %}
        <div class="kg-order-item">
            <p>课程名称：{{ course['title'] }}</p>
            <p>市场价格：{{ '￥%0.2f'|format(course['market_price']) }}，会员价格：{{ '￥%0.2f'|format(course['vip_price']) }}</p>
            <p>学习期限：{{ date('Y-m-d H:i:s',course['study_expiry_time']) }}，退款期限：{{ date('Y-m-d H:i:s',course['refund_expiry_time']) }}</p>
        </div>
    {% elseif order.item_type == 2 %}
        {% set courses = order.item_info['courses'] %}
        {% for course in courses %}
            <div class="kg-order-item">
                <p>课程名称：{{ course['title'] }}</p>
                <p>市场价格：{{ '￥%0.2f'|format(course['market_price']) }}，会员价格：{{ '￥%0.2f'|format(course['vip_price']) }}</p>
                <p>学习期限：{{ date('Y-m-d H:i:s',course['study_expiry_time']) }}，退款期限：{{ date('Y-m-d H:i:s',course['refund_expiry_time']) }}</p>
            </div>
        {% endfor %}
    {% elseif order.item_type == 3 %}
        {% set course = order.item_info['course'] %}
        {% set reward = order.item_info['reward'] %}
        <div class="kg-order-item">
            <p>商品名称：{{ order.subject }}</p>
            <p>商品价格：{{ '￥%0.2f'|format(order.amount) }}</p>
        </div>
    {% elseif order.item_type == 4 %}
        {% set vip = order.item_info['vip'] %}
        <div class="kg-order-item">
            <p>商品名称：{{ order.subject }}</p>
            <p>商品价格：{{ '￥%0.2f'|format(order.amount) }}</p>
        </div>
    {% elseif order.item_type == 99 %}
        <div class="kg-order-item">
            <p>商品名称：{{ order.subject }}</p>
            <p>商品价格：{{ '￥%0.2f'|format(order.amount) }}</p>
        </div>
    {% endif %}
{%- endmacro %}

{%- macro item_type(value) %}
    {% if value == 1 %}
        <span class="layui-badge layui-bg-green">课程</span>
    {% elseif value == 2 %}
        <span class="layui-badge layui-bg-blue">套餐</span>
    {% elseif value == 3 %}
        <span class="layui-badge layui-bg-red">赞赏</span>
    {% elseif value == 4 %}
        <span class="layui-badge layui-bg-orange">会员</span>
    {% elseif value == 99 %}
        <span class="layui-badge layui-bg-black">测试</span>
    {% endif %}
{%- endmacro %}

{%- macro order_status(value) %}
    {% if value == 1 %}
        <span class="layui-badge layui-bg-blue">待支付</span>
    {% elseif value == 2 %}
        <span class="layui-badge layui-bg-gray">发货中</span>
    {% elseif value == 3 %}
        <span class="layui-badge layui-bg-green">已完成</span>
    {% elseif value == 4 %}
        <span class="layui-badge layui-bg-cyan">已关闭</span>
    {% elseif value == 5 %}
        <span class="layui-badge layui-bg-red">已退款</span>
    {% endif %}
{%- endmacro %}