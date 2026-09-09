{%- macro target_type(value) %}
    {% if value == 1 %}
        课程
    {% elseif value == 2 %}
        单页
    {% elseif value == 3 %}
        链接
    {% elseif value == 7 %}
        会员
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}

{%- macro target_info(value) %}
    if value.course is defined %}
        {% set url = url({'for':'home.course.show','id':value.course.id}) %}
        <a href="{{ url }}" target="_blank">{{ value.course.title }}</a>（{{ value.course.id }}）
    {% elseif value.vip is defined %}
        {% set url = url({'for':'home.vip.index'}) %}
        <a href="{{ url }}" target="_blank">{{ value.vip.title }}</a>（{{ value.vip.id }}）
    {% elseif value.page is defined %}
        {% set url = url({'for':'home.page.show','id':value.page.id}) %}
        <a href="{{ url }}" target="_blank">{{ value.page.title }}</a>（{{ value.page.id }}）
    {% elseif value.link is defined %}
        <a href="{{ value.link.url }}" target="_blank">{{ value.link.url }}</a>
    {% endif %}
{%- endmacro %}
