{%- macro sale_item_type(value) %}
    {% if value == 1 %}
        课程
    {% elseif value == 2 %}
        套餐
    {% elseif value == 3 %}
        会员
    {% elseif value == 4 %}
        试卷
    {% elseif value == 5 %}
        专栏
    {% elseif value == 100 %}
        实物
    {% else %}
        N/A
    {% endif %}
{% endmacro %}
