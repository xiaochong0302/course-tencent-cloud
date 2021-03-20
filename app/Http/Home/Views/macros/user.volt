{%- macro gender_icon(value) %}
    {% if value == 1 %}
        <i class="layui-icon layui-icon-male"></i>
    {% elseif value == 2 %}
        <i class="layui-icon layui-icon-female"></i>
    {% endif %}
{%- endmacro %}

{%- macro gender_info(value) %}
    {% if value == 1 %}
        男
    {% elseif value == 2 %}
        女
    {% elseif value == 3 %}
        密
    {% endif %}
{%- endmacro %}