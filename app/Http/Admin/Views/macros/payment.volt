{%- macro channel_type(value) %}
    {% if value == 1 %}
        支付宝
    {% elseif value == 2 %}
        微信
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}
