{%- macro trade_status(value) %}
    {% if value == 'pending' %}
        <span class="layui-badge layui-bg-blue">待支付</span>
    {% elseif value == 'finished' %}
        <span class="layui-badge layui-bg-green">已完成</span>
    {% elseif value == 'closed' %}
        <span class="layui-badge layui-bg-cyan">已关闭</span>
    {% elseif value == 'refunded' %}
        <span class="layui-badge layui-bg-red">已退款</span>
    {% endif %}
{%- endmacro %}

{%- macro channel_type(value) %}
    {% if value == 'alipay' %}
        <span class="layui-badge layui-bg-blue">支付宝</span>
    {% elseif value == 'wxpay' %}
        <span class="layui-badge layui-bg-green">微信</span>
    {% endif %}
{%- endmacro %}