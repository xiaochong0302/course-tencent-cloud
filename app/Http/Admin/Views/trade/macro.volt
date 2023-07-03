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