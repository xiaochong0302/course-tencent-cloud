{%- macro publish_status(type) %}
    {% if type == 1 %}
        审核中
    {% elseif type == 2 %}
        已发布
    {% elseif type == 3 %}
        未通过
    {% else %}
        未知
    {% endif %}
{%- endmacro %}

{%- macro source_info(type,url) %}
    {% if type == 1 %}
        <span class="layui-badge">原创</span>
    {% elseif type == 2 %}
        <a class="layui-badge layui-bg-blue" href="{{ url }}" target="_blank">转载</a>
    {% elseif type == 3 %}
        <span class="layui-badge layui-bg-gray">翻译</span>
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}

{%- macro tags_info(items) %}
    {% for item in items %}
        {% set comma = loop.last ? '' : ',' %}
        {{ item.name ~ comma }}
    {% endfor %}
{%- endmacro %}