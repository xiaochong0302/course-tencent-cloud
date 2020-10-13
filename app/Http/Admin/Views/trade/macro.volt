{%- macro channel_type(value) %}
    {% if value == 1 %}
        支付宝
    {% elseif value == 2 %}
        微信
    {% endif %}
{%- endmacro %}

{%- macro trade_status(value) %}
    {% if value == 1 %}
        待支付
    {% elseif value == 2 %}
        已完成
    {% elseif value == 3 %}
        已关闭
    {% elseif value == 4 %}
        已退款
    {% endif %}
{%- endmacro %}

{%- macro trade_status_history(items) %}
    {% for item in items %}
        {% if item.status == 1 %}
            <p>创建时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 2 %}
            <p>完成时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 3 %}
            <p>关闭时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 4 %}
            <p>退款时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% endif %}
    {% endfor %}
{%- endmacro %}