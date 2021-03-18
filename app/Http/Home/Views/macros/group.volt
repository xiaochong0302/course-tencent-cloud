{%- macro type_info(value) %}
    {% if value == 1 %}
        <span class="layui-badge layui-bg-green type" title="课程交流">课程</span>
    {% elseif value == 2 %}
        <span class="layui-badge layui-bg-blue type" title="课外畅聊">水吧</span>
    {% elseif value == 3 %}
        <span class="layui-badge layui-bg-cyan type" title="职工交流">职工</span>
    {% endif %}
{%- endmacro %}