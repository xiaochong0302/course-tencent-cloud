{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>注册</cite></a>
    </div>

    <div class="account-wrap wrap">
        <form class="layui-form account-form" method="POST" action="{{ url({'for':'home.account.do_register'}) }}">
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input id="cv-account" class="layui-input" type="text" name="account" autocomplete="off" placeholder="手机 / 邮箱" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input class="layui-input" type="password" name="password" autocomplete="off" placeholder="密码（字母数字特殊字符6-16位）" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-inline verify-input-inline">
                    <input class="layui-input" type="text" name="verify_code" placeholder="验证码" lay-verify="required">
                </div>
                <div class="layui-input-inline verify-btn-inline">
                    <button id="cv-verify-emit" class="layui-btn layui-btn-primary layui-btn-disabled" type="button" disabled="disabled">获取验证码</button>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button id="cv-submit-btn" class="layui-btn layui-btn-fluid layui-btn-disabled" disabled="disabled" lay-submit="true" lay-filter="go">注册帐号</button>
                    <input type="hidden" name="return_url" value="{{ return_url }}">
                    <input id="cv-app-id" type="hidden" value="{{ captcha.app_id }}">
                    <input id="cv-ticket" type="hidden" name="ticket">
                    <input id="cv-rand" type="hidden" name="rand">
                </div>
            </div>
        </form>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('https://ssl.captcha.qq.com/TCaptcha.js',false) }}
    {{ js_include('home/js/captcha.verify.js') }}

{% endblock %}
