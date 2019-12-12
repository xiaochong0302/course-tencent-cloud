{%- macro refund_status(refund) %}
    {% if refund.status == 'pending' %}
        <span class="layui-badge layui-bg-blue">待处理</span>
    {% elseif refund.status == 'canceled' %}
        <span class="layui-badge layui-bg-gray">已取消</span>
    {% elseif refund.status == 'approved' %}
        <span class="layui-badge layui-bg-orange" title="{{ refund.review_note }}">已审核</span>
    {% elseif refund.status == 'refused' %}
        <span class="layui-badge layui-bg-red" title="{{ refund.review_note }}">已拒绝</span>
    {% elseif refund.status == 'finished' %}
        <span class="layui-badge layui-bg-green">已完成</span>
    {% elseif refund.status == 'failed' %}
        <span class="layui-badge layui-bg-cyan">已失败</span>
    {% endif %}
{%- endmacro %}