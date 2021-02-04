{%- macro gift_type_info(value) %}
    {% if value == 1 %}
        <span class="layui-badge layui-bg-green type">课程</span>
    {% elseif value == 2 %}
        <span class="layui-badge layui-bg-blue type">商品</span>
    {% elseif value == 3 %}
        <span class="layui-badge layui-bg-cyan type">现金</span>
    {% endif %}
{%- endmacro %}