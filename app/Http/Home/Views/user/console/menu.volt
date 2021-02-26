{%- macro vip_info(user) %}
    {% set vip_url = url({'for':'home.vip.index'}) %}
    {% if user.vip == 1 %}
        <a class="layui-badge layui-bg-orange" title="到期时间：{{ date('Y-m-d',user.vip_expiry_time) }}" href="{{ vip_url }}">会员</a>
    {% else %}
        <a class="layui-badge layui-bg-gray" title="开通会员" href="{{ vip_url }}">会员</a>
    {% endif %}
{%- endmacro %}

{% set wechat_oa_enabled = setting('wechat.oa','enabled') %}
{% set point_enabled = setting('point','enabled') %}

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
            <li><a href="{{ url({'for':'home.uc.courses'}) }}">我的课程</a></li>
            <li><a href="{{ url({'for':'home.uc.favorites'}) }}">我的收藏</a></li>
            <li><a href="{{ url({'for':'home.uc.reviews'}) }}">我的评价</a></li>
            <li><a href="{{ url({'for':'home.uc.consults'}) }}">我的咨询</a></li>
        </ul>
    </div>
</div>

<div class="layui-card">
    <div class="layui-card-header">订单中心</div>
    <div class="layui-card-body">
        <ul class="my-menu">
            <li><a href="{{ url({'for':'home.uc.orders'}) }}">我的订单</a></li>
            <li><a href="{{ url({'for':'home.uc.refunds'}) }}">我的退款</a></li>
        </ul>
    </div>
</div>

{% if point_enabled == 1 %}
    <div class="layui-card">
        <div class="layui-card-header">积分中心</div>
        <div class="layui-card-body">
            <ul class="my-menu">
                <li><a href="{{ url({'for':'home.point_gift.list'}) }}">积分商城</a></li>
                <li><a href="{{ url({'for':'home.uc.point_redeems'}) }}">兑换记录</a></li>
                <li><a href="{{ url({'for':'home.uc.point_history'}) }}">积分记录</a></li>
            </ul>
        </div>
    </div>
{% endif %}

<div class="layui-card">
    <div class="layui-card-header">聊天设置</div>
    <div class="layui-card-body">
        <ul class="my-menu">
            <li><a href="{{ url({'for':'home.uc.friends'}) }}">我的好友</a></li>
            <li><a href="{{ url({'for':'home.uc.groups'}) }}">我的群组</a></li>
        </ul>
    </div>
</div>

<div class="layui-card">
    <div class="layui-card-header">个人设置</div>
    <div class="layui-card-body">
        <ul class="my-menu">
            <li><a href="{{ url({'for':'home.uc.profile'}) }}">个人信息</a></li>
            <li><a href="{{ url({'for':'home.uc.contact'}) }}">收货地址</a></li>
            <li><a href="{{ url({'for':'home.uc.account'}) }}">帐号安全</a></li>
            {% if wechat_oa_enabled == 1 %}
                <li><a href="{{ url({'for':'home.uc.subscribe'}) }}">关注订阅</a></li>
            {% endif %}
        </ul>
    </div>
</div>