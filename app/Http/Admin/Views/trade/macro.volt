{%- macro channel_type(value) %}
    {% if value == '1' %}
        <span class="layui-badge layui-bg-blue">支付宝</span>
    {% elseif value == '2' %}
        <span class="layui-badge layui-bg-green">微信</span>
    {% endif %}
{%- endmacro %}

{%- macro trade_status(value) %}
    {% if value == 1 %}
        <span class="layui-badge layui-bg-blue">待支付</span>
    {% elseif value == 2 %}
        <span class="layui-badge layui-bg-green">已完成</span>
    {% elseif value == 3 %}
        <span class="layui-badge layui-bg-cyan">已关闭</span>
    {% elseif value == 4 %}
        <span class="layui-badge layui-bg-red">已退款</span>
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