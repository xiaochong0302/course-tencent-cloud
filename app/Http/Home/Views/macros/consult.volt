{%- macro priority_type(value) %}
    {% if value == 10 %}
        高
    {% elseif value == 20 %}
        中
    {% elseif value == 30 %}
        低
    {% endif %}
{%- endmacro %}