{%- macro source_type(type) %}
    {% if type == 1 %}
        原创
    {% elseif type == 2 %}
        转载
    {% elseif type == 3 %}
        翻译
    {% else %}
        未知
    {% endif %}
{%- endmacro %}