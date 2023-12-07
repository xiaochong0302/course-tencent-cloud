{%- macro publish_status(type) %}
    {% if type == 1 %}
        审核中
    {% elseif type == 2 %}
        已发布
    {% elseif type == 3 %}
        未通过
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}

{%- macro source_type(type) %}
    {% if type == 1 %}
        原创
    {% elseif type == 2 %}
        转载
    {% elseif type == 3 %}
        翻译
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}