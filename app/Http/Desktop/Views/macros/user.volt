{%- macro vip_info(value) %}
    {% if value == 1 %}
        <span class="layui-badge layui-bg-orange vip">宾</span>
    {% endif %}
{%- endmacro %}

{%- macro gender_info(value) %}
    {% if value == 1 %}
        <i class="layui-icon layui-icon-male"></i>
    {% elseif value == 2 %}
        <i class="layui-icon layui-icon-female"></i>
    {% endif %}
{%- endmacro %}