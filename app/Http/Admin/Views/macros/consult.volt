{%- macro publish_status(type) %}
    {% if type == 1 %}
        审核中
    {% elseif type == 2 %}
        已发布
    {% elseif type == 3 %}
        未通过
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}

{%- macro private_info(value) %}
    {% if value == 1 %}
        <span class="layui-badge">私密</span>
    {% endif %}
{%- endmacro %}