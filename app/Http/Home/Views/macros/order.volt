{%- macro item_info(order) %}
    {% if order.item_type == 1 %}
        {% set course = order.item_info.course %}
        <div class="order-item">
            <p>课程名称：<span>{{ course.title }}</span></p>
            <p>优惠价格：<span class="price">{{ '￥%0.2f'|format(course.market_price) }}</span>会员价格：<span class="price">{{ '￥%0.2f'|format(course.vip_price) }}</span></p>
            <p>学习期限：<span>{{ date('Y-m-d H:i:s',course.study_expiry_time) }}</span>退款期限：<span>{% if course.refund_expiry > 0 %}{{ date('Y-m-d H:i:s',course.refund_expiry_time) }}{% else %}不支持{% endif %}</span></p>
        </div>
    {% elseif order.item_type == 2 %}
        {% set courses = order.item_info.courses %}
        {% for course in courses %}
            <div class="order-item">
                <p>课程名称：<span>{{ course.title }}</span></p>
                <p>优惠价格：<span class="price">{{ '￥%0.2f'|format(course.market_price) }}</span>会员价格：<span class="price">{{ '￥%0.2f'|format(course.vip_price) }}</span></p>
                <p>学习期限：<span>{{ date('Y-m-d H:i:s',course.study_expiry_time) }}</span>退款期限：<span>{% if course.refund_expiry > 0 %}{{ date('Y-m-d H:i:s',course.refund_expiry_time) }}{% else %}不支持{% endif %}</span></p>
            </div>
        {% endfor %}
    {% elseif order.item_type == 3 %}
        {% set course = order.item_info.course %}
        {% set reward = order.item_info.reward %}
        <div class="order-item">
            <p>课程名称：<span>{{ course.title }}</span></p>
            <p>赞赏金额：<span class="price">{{ '￥%0.2f'|format(reward.price) }}</span></p>
        </div>
    {% elseif order.item_type == 4 %}
        {% set vip = order.item_info.vip %}
        <div class="order-item">
            <p>商品名称：<span>{{ order.subject }}</span></p>
            <p>商品价格：<span class="price">{{ '￥%0.2f'|format(order.amount) }}</span></p>
        </div>
    {% elseif order.item_type == 99 %}
        <div class="order-item">
            <p>商品名称：<span>{{ order.subject }}</span></p>
            <p>商品价格：<span class="price">{{ '￥%0.2f'|format(order.amount) }}</span></p>
        </div>
    {% endif %}
{%- endmacro %}

{%- macro status_history(items) %}
    {% for item in items %}
        {% if item.status == 1 %}
            <p>创建时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 2 %}
            <p>支付时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 3 %}
            <p>完成时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 4 %}
            <p>关闭时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 5 %}
            <p>退款时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% endif %}
    {% endfor %}
{%- endmacro %}

{%- macro order_status(value) %}
    {% if value == 1 %}
        待支付
    {% elseif value == 2 %}
        发货中
    {% elseif value == 3 %}
        已完成
    {% elseif value == 4 %}
        已关闭
    {% elseif value == 5 %}
        已退款
    {% endif %}
{%- endmacro %}

{%- macro promotion_type(value) %}
    {% if value == 0 %}
        N/A
    {% elseif value == 1 %}
        秒杀
    {% elseif value == 2 %}
        折扣
    {% endif %}
{%- endmacro %}
