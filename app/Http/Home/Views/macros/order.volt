{%- macro item_info(order) %}
    {% if order.item_type == 1 %}
        {% set course = order.item_info.course %}
        <div class="order-item">
            <p>课程名称：{{ course.title }}</p>
            <p>
                <span>市场价格：<em class="price">{{ '￥%0.2f'|format(course.market_price) }}</em></span>
                <span>会员价格：<em class="price">{{ '￥%0.2f'|format(course.vip_price) }}</em></span>
            </p>
            {% if course.model in [1,2,3] %}
                <p>
                    <span>学习期限：{{ date('Y-m-d',course.study_expiry_time) }}</span>
                    <span>退款期限：{{ course.refund_expiry > 0 ? date('Y-m-d',course.refund_expiry_time) : '不支持' }}</span>
                </p>
            {% endif %}
        </div>
    {% elseif order.item_type == 3 %}
        {% set vip = order.item_info.vip %}
        <div class="order-item">
            <p>商品名称：{{ order.subject }}</p>
            <p>商品价格：<em class="price">{{ '￥%0.2f'|format(order.amount) }}</em></p>
        </div>
    {% elseif order.item_type == 98 %}
        <div class="order-item">
            <p>商品名称：{{ order.subject }}</p>
            <p>商品价格：<em class="price">{{ '￥%0.2f'|format(order.amount) }}</em></p>
        </div>
    {% elseif order.item_type == 99 %}
        <div class="order-item">
            <p>商品名称：{{ order.subject }}</p>
            <p>商品价格：<em class="price">{{ '￥%0.2f'|format(order.amount) }}</em></p>
        </div>
    {% endif %}
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
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}
