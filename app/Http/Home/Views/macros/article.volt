{%- macro source_type(value) %}
    {% if value == 1 %}
        原创
    {% elseif value == 2 %}
        转载
    {% elseif value == 3 %}
        翻译
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}
