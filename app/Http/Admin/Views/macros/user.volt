{%- macro gender_info(value) %}
    {% if value == 1 %}
        男
    {% elseif value == 2 %}
        女
    {% elseif value == 3 %}
        密
    {% endif %}
{%- endmacro %}

{%- macro edu_role_info(role) %}
    {% if role.id == 1 %}
        学员
    {% elseif role.id == 2 %}
        讲师
    {% endif %}
{%- endmacro %}

{%- macro admin_role_info(role) %}
    {% if role.id > 0 %}
        {{ role.name }}
    {% else %}
        N/A
    {% endif %}
{%- endmacro %}

{%- macro status_info(user) %}
    {% if user.vip == 1 %}
        <span class="layui-badge layui-bg-orange" title="期限：{{ date('Y-m-d H:i:s',user.vip_expiry_time) }}">会员</span>
    {% endif %}
    {% if user.locked == 1 %}
        <span class="layui-badge" title="期限：{{ date('Y-m-d H:i:s',user.lock_expiry_time) }}">锁定</span>
    {% endif %}
{%- endmacro %}