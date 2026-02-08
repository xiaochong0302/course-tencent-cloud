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

{%- macro source_type_badge(value) %}
    {% if value == 1 %}
        <span class="layui-badge layui-bg-green">原创</span>
    {% elseif value == 2 %}
        <span class="layui-badge layui-bg-orange">转载</span>
    {% elseif value == 3 %}
        <span class="layui-badge layui-bg-blue">翻译</span>
    {% endif %}
{%- endmacro %}
