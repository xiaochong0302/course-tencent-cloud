{%- macro product_type(value) %}
    {% if value == 1 %}
        课程
    {% elseif value == 3 %}
        会员
    {% elseif value == 99 %}
        支付测试
    {% else %}
        N/A
    {% endif %}
{% endmacro %}
