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
            {% elseif course.model == 4 %}
                <p>上课时间：{{ course.attrs.start_date }} ~ {{ course.attrs.end_date }}</p>
                <p>上课地点：{{ course.attrs.location }}</p>
            {% endif %}
        </div>
    {% elseif order.item_type == 2 %}
        {% set courses = order.item_info.courses %}
        {% for course in courses %}
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
        {% endfor %}
    {% elseif order.item_type == 4 %}
        {% set vip = order.item_info.vip %}
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
