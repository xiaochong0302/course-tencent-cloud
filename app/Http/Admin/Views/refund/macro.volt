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