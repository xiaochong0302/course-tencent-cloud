{%- macro target_info(value) %}
    {% if value == 1 %}
        <span class="layui-badge layui-bg-green">课程</span>
    {% elseif value == 2 %}
        <span class="layui-badge layui-bg-blue">单页</span>
    {% elseif value == 3 %}
        <span class="layui-badge layui-bg-red">链接</span>
    {% endif %}
{%- endmacro %}

{%- macro target_attrs_info(value) %}
    {% if value.course is defined %}
        {% set url = url({'for':'home.course.show','id':value.course.id}) %}
        <a href="{{ url }}" target="_blank">{{ value.course.title }}</a>（{{ value.course.id }}）
    {% elseif value.page is defined %}
        {% set url = url({'for':'home.page.show','id':value.page.id}) %}
        <a href="{{ url }}" target="_blank">{{ value.page.title }}</a>（{{ value.page.id }}）
    {% elseif value.link is defined %}
        <a href="{{ value.link.url }}" target="_blank">{{ value.link.url }}</a>
    {% endif %}
{%- endmacro %}