{%- macro type_info(value) %}
    {% if value == 1 %}
        课程
    {% elseif value == 2 %}
        水吧
    {% elseif value == 3 %}
        职工
    {% endif %}
{%- endmacro %}