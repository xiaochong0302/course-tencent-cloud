{%- macro expiry_info(value) %}
    {% if value == 30 %}
        一个月
    {% elseif value == 90 %}
        三个月
    {% elseif value == 180 %}
        半年
    {% elseif value == 365 %}
        一年
    {% elseif value == 1095 %}
        三年
    {% endif %}
{%- endmacro %}