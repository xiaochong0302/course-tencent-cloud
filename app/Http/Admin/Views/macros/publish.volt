{%- macro publish_status(value) %}
    {% if value == 1 %}
        审核中
    {% elseif value == 2 %}
        已发布
    {% elseif value == 3 %}
        未通过
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}
