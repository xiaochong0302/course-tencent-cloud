{%- macro product_type(value) %}
    {% if value == 1 %}
        课程
    {% elseif value == 3 %}
        会员
    {% elseif value == 99 %}
        支付测试
    {% else %}
        N/A
    {% endif %}
{% endmacro %}

{%- macro product_type_badge(value) %}
    {% if value == 1 %}
        <span class="layui-badge layui-bg-green">课程</span>
    {% elseif value == 3 %}
        <span class="layui-badge layui-bg-orange">会员</span>
    {% endif %}
{%- endmacro %}

{%- macro get_product_url(type,id) %}
    {% if type == 1 %}
        {% set url = url({'for':'home.course.show','id':id}) %}
    {% elseif type == 3 %}
        {% set url = url({'for':'home.vip.index'}) %}
    {% else %}
        {% set url = url({'for':'home.index'}) %}
    {% endif %}
    {% return url %}
{%- endmacro %}
