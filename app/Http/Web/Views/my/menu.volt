{%- macro vip_info(user) %}
    {% set vip_url = url({'for':'web.vip.index'}) %}
    {% if user.vip == 1 %}
        <a class="layui-badge layui-bg-orange" title="到期时间：{{ date('Y-m-d',user.vip_expiry_time) }}" href="{{ vip_url }}">会员</a>
    {% else %}
        <a class="layui-badge layui-bg-gray" title="开通会员" href="{{ vip_url }}">会员</a>
    {% endif %}
{%- endmacro %}

<div class="my-profile-card wrap">
    <div class="avatar">
        <a href="javascript:"><img src="{{ auth_user.avatar }}" alt="{{ auth_user.name }}"></a>
    </div>
    <div class="name">{{ auth_user.name }} {{ vip_info(auth_user) }}</div>
</div>

<div class="layui-card">
    <div class="layui-card-header">个人设置</div>
    <div class="layui-card-body">
        <ul class="my-menu">
            <li><a href="{{ url({'for':'web.my.profile'}) }}">个人信息</a></li>
            <li><a href="{{ url({'for':'web.my.account'}) }}">帐号安全</a></li>
        </ul>
    </div>
</div>