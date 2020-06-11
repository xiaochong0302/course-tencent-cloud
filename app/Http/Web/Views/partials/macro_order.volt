{%- macro item_info(order) %}
    {% if order.item_type == 'course' %}
        {% set course = order.item_info.course %}
        {% set course_url = url({'for':'web.course.show','id':course.id}) %}
        <div class="order-item">
            <p>课程名称：<a href="{{ course_url }}" target="_blank">{{ course.title }}</a></p>
            <p>市场价格：<span class="price">{{ '￥%0.2f'|format(course.market_price) }}</span>会员价格：<span class="price">{{ '￥%0.2f'|format(course.vip_price) }}</span></p>
            <p>学习期限：{{ date('Y-m-d H:i:s',course.study_expiry_time) }}<span class="space"></span>退款期限：{{ date('Y-m-d H:i:s',course.refund_expiry_time) }}</p>
        </div>
    {% elseif order.item_type == 'package' %}
        {% set courses = order.item_info.courses %}
        {% for course in courses %}
            {% set course_url = url({'for':'web.course.show','id':course.id}) %}
            <div class="order-item">
                <p>课程名称：<a href="{{ course_url }}" target="_blank">{{ course.title }}</a></p>
                <p>市场价格：<span class="price">{{ '￥%0.2f'|format(course.market_price) }}</span>会员价格：<span class="price">{{ '￥%0.2f'|format(course.vip_price) }}</span></p>
                <p>学习期限：{{ date('Y-m-d H:i:s',course.study_expiry_time) }}<span class="space"></span>退款期限：{{ date('Y-m-d H:i:s',course.refund_expiry_time) }}</p>
            </div>
        {% endfor %}
    {% elseif order.item_type == 'vip' %}
        {% set vip = order.item_info.vip %}
        <div class="order-item">
            <p>商品名称：{{ order.subject }}</p>
            <p>商品价格：<span class="price">{{ '￥%0.2f'|format(order.amount) }}</span></p>
        </div>
    {% elseif order.item_type == 'reward' %}
        {% set course = order.item_info.course %}
        {% set reward = order.item_info.reward %}
        {% set course_url = url({'for':'web.course.show','id':course.id}) %}
        <div class="order-item">
            <p>课程名称：<a href="{{ course_url }}" target="_blank">{{ course.title }}</a></p>
            <p>赞赏金额：<span class="price">{{ '￥%0.2f'|format(reward.price) }}</span></p>
        </div>
    {% elseif order.item_type == 'test' %}
        <div class="order-item">
            <p>商品名称：{{ order.subject }}</p>
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

{%- macro item_type(value) %}
    {% if value == 'course' %}
        课程
    {% elseif value == 'package' %}
        套餐
    {% elseif value == 'vip' %}
        会员
    {% elseif value == 'reward' %}
        赞赏
    {% elseif value == 'test' %}
        测试
    {% endif %}
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
