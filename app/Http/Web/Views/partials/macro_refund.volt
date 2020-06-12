{%- macro status_history(items) %}
    {% for item in items %}
        {% if item.status == 'pending' %}
            <p>创建时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 'canceled' %}
            <p>取消时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 'approved' %}
            <p>审核时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 'refused' %}
            <p>拒绝时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 'finished' %}
            <p>完成时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 'failed' %}
            <p>失败时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% endif %}
    {% endfor %}
{%- endmacro %}

{%- macro refund_status(value) %}
    {% if value == 'pending' %}
        待处理
    {% elseif value == 'canceled' %}
        已取消
    {% elseif value == 'approved' %}
        退款中
    {% elseif value == 'refused' %}
        已拒绝
    {% elseif value == 'finished' %}
        已完成
    {% elseif value == 'failed' %}
        已失败
    {% endif %}
{%- endmacro %}
