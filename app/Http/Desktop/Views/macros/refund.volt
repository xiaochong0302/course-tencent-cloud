{%- macro status_history(items) %}
    {% for item in items %}
        {% if item.status == 1 %}
            <p>创建时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 2 %}
            <p>取消时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 3 %}
            <p>审核时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 4 %}
            <p>拒绝时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 5 %}
            <p>完成时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 6 %}
            <p>失败时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% endif %}
    {% endfor %}
{%- endmacro %}

{%- macro refund_status(value) %}
    {% if value == 1 %}
        待处理
    {% elseif value == 2 %}
        已取消
    {% elseif value == 3 %}
        退款中
    {% elseif value == 4 %}
        已拒绝
    {% elseif value == 5 %}
        已完成
    {% elseif value == 6 %}
        已失败
    {% endif %}
{%- endmacro %}
