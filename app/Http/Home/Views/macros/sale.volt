{%- macro sale_item_type(value) %}
    {% if value == 1 %}
        课程
    {% elseif value == 2 %}
        套餐
    {% elseif value == 3 %}
        会员
    {% elseif value == 4 %}
        试卷
    {% elseif value == 5 %}
        专栏
    {% elseif value == 100 %}
        实物
    {% else %}
        N/A
    {% endif %}
{% endmacro %}

{%- macro sale_item_type_badge(value) %}
    {% if value == 1 %}
        <span class="layui-badge layui-bg-green">课程</span>
    {% elseif value == 2 %}
        <span class="layui-badge layui-bg-purple">套餐</span>
    {% elseif value == 3 %}
        <span class="layui-badge layui-bg-orange">会员</span>
    {% elseif value == 4 %}
        <span class="layui-badge layui-bg-blue">试卷</span>
    {% elseif value == 5 %}
        <span class="layui-badge layui-bg-cyan">专栏</span>
    {% elseif value == 100 %}
        <span class="layui-badge layui-bg-red">实物</span>
    {% endif %}
{%- endmacro %}
