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
        <img class="my-avatar" src="{{ auth_user.avatar }}" alt="{{ auth_user.name }}">
    </div>
    <div class="name">{{ auth_user.name }} {{ vip_info(auth_user) }}</div>
</div>

<div class="layui-card">
    <div class="layui-card-header">课程中心</div>
    <div class="layui-card-body">
        <ul class="my-menu">
            <li><a href="{{ url({'for':'web.my.courses'}) }}">我的课程</a></li>
            <li><a href="{{ url({'for':'web.my.favorites'}) }}">我的收藏</a></li>
            <li><a href="{{ url({'for':'web.my.reviews'}) }}">我的评价</a></li>
            <li><a href="{{ url({'for':'web.my.consults'}) }}">我的咨询</a></li>
        </ul>
    </div>
</div>

<div class="layui-card">
    <div class="layui-card-header">订单中心</div>
    <div class="layui-card-body">
        <ul class="my-menu">
            <li><a href="{{ url({'for':'web.my.orders'}) }}">我的订单</a></li>
            <li><a href="{{ url({'for':'web.my.refunds'}) }}">我的退款</a></li>
        </ul>
    </div>
</div>

<div class="layui-card">
    <div class="layui-card-header">聊天设置</div>
    <div class="layui-card-body">
        <ul class="my-menu">
            <li><a href="{{ url({'for':'web.my.friends'}) }}">我的好友</a></li>
            <li><a href="{{ url({'for':'web.my.groups'}) }}">我的群组</a></li>
        </ul>
    </div>
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