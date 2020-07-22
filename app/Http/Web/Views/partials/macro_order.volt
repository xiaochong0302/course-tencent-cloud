{%- macro item_info(order) %}
    {% if order.item_type == 'course' %}
        {% set course = order.item_info.course %}
        <div class="order-item">
            <p>课程名称：<span>{{ course.title }}</span></p>
            <p>市场价格：<span class="price">{{ '￥%0.2f'|format(course.market_price) }}</span>会员价格：<span class="price">{{ '￥%0.2f'|format(course.vip_price) }}</span></p>
            <p>学习期限：<span>{{ date('Y-m-d',course.study_expiry_time) }}</span>退款期限：<span>{{ date('Y-m-d',course.refund_expiry_time) }}</span></p>
        </div>
    {% elseif order.item_type == 'package' %}
        {% set courses = order.item_info.courses %}
        {% for course in courses %}
            <div class="order-item">
                <p>课程名称：<span>{{ course.title }}</span></p>
                <p>市场价格：<span class="price">{{ '￥%0.2f'|format(course.market_price) }}</span>会员价格：<span class="price">{{ '￥%0.2f'|format(course.vip_price) }}</span></p>
                <p>学习期限：<span>{{ date('Y-m-d',course.study_expiry_time) }}</span>退款期限：<span>{{ date('Y-m-d',course.refund_expiry_time) }}</span></p>
            </div>
        {% endfor %}
    {% elseif order.item_type == 'vip' %}
        {% set vip = order.item_info.vip %}
        <div class="order-item">
            <p>商品名称：<span>{{ order.subject }}</span></p>
            <p>商品价格：<span class="price">{{ '￥%0.2f'|format(order.amount) }}</span></p>
        </div>
    {% elseif order.item_type == 'reward' %}
        {% set course = order.item_info.course %}
        {% set reward = order.item_info.reward %}
        <div class="order-item">
            <p>课程名称：<span>{{ course.title }}</span></p>
            <p>赞赏金额：<span class="price">{{ '￥%0.2f'|format(reward.price) }}</span></p>
        </div>
    {% elseif order.item_type == 'test' %}
        <div class="order-item">
            <p>商品名称：<span>{{ order.subject }}</span></p>
            <p>商品价格：<span class="price">{{ '￥%0.2f'|format(order.amount) }}</span></p>
        </div>
    {% endif %}
{%- endmacro %}

{%- macro status_history(items) %}
    {% for item in items %}
        {% if item.status == 'pending' %}
            <p>创建时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 'finished' %}
            <p>完成时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 'closed' %}
            <p>关闭时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 'refunded' %}
            <p>退款时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% endif %}
    {% endfor %}
{%- endmacro %}

{%- macro order_status(value) %}
    {% if value == 'pending' %}
        待支付
    {% elseif value == 'finished' %}
        已完成
    {% elseif value == 'closed' %}
        已关闭
    {% elseif value == 'refunded' %}
        已退款
    {% endif %}
{%- endmacro %}
