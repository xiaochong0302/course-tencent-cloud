{%- macro vip_info(value) %}
    {% if value == 1 %}
        <span class="layui-badge layui-bg-orange vip">宾</span>
    {% endif %}
{%- endmacro %}