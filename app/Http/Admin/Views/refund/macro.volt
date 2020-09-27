{%- macro refund_status(value) %}
    {% if value == 1 %}
        <span class="layui-badge layui-bg-blue">待处理</span>
    {% elseif value == 2 %}
        <span class="layui-badge layui-bg-gray">已取消</span>
    {% elseif value == 3 %}
        <span class="layui-badge layui-bg-orange">已审核</span>
    {% elseif value == 4 %}
        <span class="layui-badge layui-bg-red">已拒绝</span>
    {% elseif value == 5 %}
        <span class="layui-badge layui-bg-green">已完成</span>
    {% elseif value == 6 %}
        <span class="layui-badge layui-bg-cyan">已失败</span>
    {% endif %}
{%- endmacro %}

{%- macro refund_status_history(items) %}
    {% for item in items %}
        {% if item.status == 1 %}
            <p>创建时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 2 %}
            <p>取消时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 3 %}
            <p>过审时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 4 %}
            <p>拒绝时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 5 %}
            <p>完成时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% elseif item.status == 6 %}
            <p>失败时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
        {% endif %}
    {% endfor %}
{%- endmacro %}