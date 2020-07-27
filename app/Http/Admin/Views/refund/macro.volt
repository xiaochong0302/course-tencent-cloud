{%- macro refund_status(value) %}
    {% if value == 'pending' %}
        <span class="layui-badge layui-bg-blue">待处理</span>
    {% elseif value == 'canceled' %}
        <span class="layui-badge layui-bg-gray">已取消</span>
    {% elseif value == 'approved' %}
        <span class="layui-badge layui-bg-orange">已审核</span>
    {% elseif value == 'refused' %}
        <span class="layui-badge layui-bg-red">已拒绝</span>
    {% elseif value == 'finished' %}
        <span class="layui-badge layui-bg-green">已完成</span>
    {% elseif value == 'failed' %}
        <span class="layui-badge layui-bg-cyan">已失败</span>
    {% endif %}
{%- endmacro %}

{%- macro refund_status_history(items) %}
    {% for item in items %}
        {% if item.status == 'pending' %}
            <p>创建时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 'canceled' %}
            <p>取消时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 'approved' %}
            <p>过审时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 'refused' %}
            <p>拒绝时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 'finished' %}
            <p>完成时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 'failed' %}
            <p>失败时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% endif %}
    {% endfor %}
{%- endmacro %}