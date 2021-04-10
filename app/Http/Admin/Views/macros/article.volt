{%- macro source_info(type,url) %}
    {% if type == 1 %}
        <span>原创</span>
    {% elseif type == 2 %}
        <a href="{{ url }}" target="_blank">转载</a>
    {% elseif type == 3 %}
        <span>翻译</span>
    {% else %}
        <span>N/A</span>
    {% endif %}
{%- endmacro %}

{%- macro tags_info(items) %}
    {% for item in items %}
        {% set comma = loop.last ? '' : ',' %}
        {{ item.name ~ comma }}
    {% endfor %}
{%- endmacro %}