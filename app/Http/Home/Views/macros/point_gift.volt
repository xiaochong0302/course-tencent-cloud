{%- macro gift_type(value) %}
    {% if value == 1 %}
        课程
    {% elseif value == 2 %}
        商品
    {% elseif value == 3 %}
        会员
    {% endif %}
{%- endmacro %}

{%- macro redeem_status(value) %}
    {% if value == 1 %}
        处理中
    {% elseif value == 2 %}
        已完成
    {% elseif value == 3 %}
        已失败
    {% endif %}
{%- endmacro %}