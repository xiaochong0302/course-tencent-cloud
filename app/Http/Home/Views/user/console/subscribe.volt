{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">关注订阅</span>
                </div>
                <div class="my-subscribe">
                    {% if subscribed == 0 %}
                        <div id="sub-qrcode" class="qrcode"></div>
                        <div id="sub-tips" class="tips">订阅官方公众号，接收重要通知！</div>
                    {% else %}
                        <div class="tips">你已经订阅官方公众号</div>
                    {% endif %}
                </div>
                <div class="layui-hide">
                    <input type="hidden" name="subscribed" value="{{ subscribed }}">
                </div>
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/wechat.oa.subscribe.js') }}

{% endblock %}