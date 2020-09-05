{%- macro priority_info(value) %}
    {% if value == 10 %}
        <span class="layui-badge layui-bg-red">高</span>
    {% elseif value == 20 %}
        <span class="layui-badge layui-bg-blue">中</span>
    {% elseif value == 30 %}
        <span class="layui-badge layui-bg-gray">低</span>
    {% endif %}
{%- endmacro %}