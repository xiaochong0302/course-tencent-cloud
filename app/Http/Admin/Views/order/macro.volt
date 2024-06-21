{%- macro item_info(order) %}
    {% if order.item_type == 1 %}
        {% set course = order.item_info['course'] %}
        <div class="kg-order-item">
            <p>课程名称：{{ course['title'] }}</p>
            <p>
                <span>市场价格：{{ '￥%0.2f'|format(course['market_price']) }}</span>
                <span>会员价格：{{ '￥%0.2f'|format(course['vip_price']) }}</span>
            </p>
            {% if course['model'] in [1,2,3] %}
                <p>
                    <span>学习期限：{{ date('Y-m-d H:i:s',course['study_expiry_time']) }}</span>
                    <span>退款期限：{{ date('Y-m-d H:i:s',course['refund_expiry_time']) }}</span>
                </p>
            {% elseif course['model'] == 4 %}
                <p>上课时间：{{ course['attrs']['start_date'] }} ~ {{ course['attrs']['end_date'] }}</p>
                <p>上课地点：{{ course['attrs']['location'] }}</p>
            {% endif %}
        </div>
    {% elseif order.item_type == 2 %}
        {% set courses = order.item_info['courses'] %}
        {% for course in courses %}
            <div class="kg-order-item">
                <p>课程名称：{{ course['title'] }}</p>
                <p>
                    <span>市场价格：{{ '￥%0.2f'|format(course['market_price']) }}</span>
                    <span>会员价格：{{ '￥%0.2f'|format(course['vip_price']) }}</span>
                </p>
                <p>
                    {% if course['model'] in [1,2,3] %}
                        <span>学习期限：{{ date('Y-m-d H:i:s',course['study_expiry_time']) }}</span>
                        <span>退款期限：{{ date('Y-m-d H:i:s',course['refund_expiry_time']) }}</span>
                    {% endif %}
                </p>
            </div>
        {% endfor %}
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
        课程
    {% elseif value == 2 %}
        套餐
    {% elseif value == 3 %}
        赞赏
    {% elseif value == 4 %}
        会员
    {% elseif value == 99 %}
        测试
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
    {% endif %}
{%- endmacro %}