{% extends 'templates/layer.volt' %}

{% block content %}

    <form class="layui-form account-form" method="POST" action="{{ url({'for':'home.account.update_phone'}) }}">
        <br><br>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input class="layui-input" type="password" name="login_password" placeholder="登录密码" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input id="cv-account" class="layui-input" type="text" name="phone" placeholder="手机号码" data-type="phone" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="verify-input-inline">
                <input class="layui-input" type="text" name="verify_code" placeholder="验证码" lay-verify="required">
            </div>
            <div class="verify-btn-inline">
                <button id="cv-verify-emit" class="layui-btn layui-btn-primary layui-btn-disabled" type="button" disabled="disabled">获取验证码</button>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button id="cv-submit-btn" class="layui-btn layui-btn-fluid layui-btn-disabled" disabled="disabled" lay-submit="true" lay-filter="go">立即绑定</button>
                <input id="cv-app-id" type="hidden" value="{{ captcha.app_id }}">
                <input id="cv-ticket" type="hidden" name="ticket">
                <input id="cv-rand" type="hidden" name="rand">
            </div>
        </div>
    </form>

{% endblock %}

{% block include_js %}

    {{ js_include('https://ssl.captcha.qq.com/TCaptcha.js',false) }}
    {{ js_include('home/js/captcha.verify.js') }}

{% endblock %}