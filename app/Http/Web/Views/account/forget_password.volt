{% extends 'templates/base.volt' %}

{% block content %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>重置密码</cite></a>
    </div>

    <div class="register-container">
        <form class="layui-form account-form" method="POST" action="{{ url({'for':'web.account.reset_pwd'}) }}">
            <div class="layui-form-item">
                <label class="layui-form-label"><i class="layui-icon layui-icon-username"></i></label>
                <div class="layui-input-block">
                    <input id="cv-account" class="layui-input" type="text" name="account" autocomplete="off" placeholder="手机 / 邮箱" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><i class="layui-icon layui-icon-password"></i></label>
                <div class="layui-input-block">
                    <input class="layui-input" type="password" name="new_password" autocomplete="off" placeholder="新密码" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><i class="layui-icon layui-icon-vercode"></i></label>
                <div class="layui-inline verify-input-inline">
                    <input class="layui-input" type="text" name="verify_code" placeholder="验证码" lay-verify="required">
                </div>
                <div class="layui-inline verify-btn-inline">
                    <button id="cv-verify-emit" class="layui-btn layui-btn-primary layui-btn-disabled" type="button">获取验证码</button>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <span id="cv-captcha-btn" class="layui-btn layui-btn-primary layui-btn-fluid" data-app-id="{{ captcha.app_id }}">点击完成验证</span>
                    <span id="cv-verify-btn" class="verify-btn-ok layui-btn layui-btn-primary layui-btn-disabled layui-btn-fluid layui-hide"><i class="layui-icon layui-icon-ok"></i>验证成功</span>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button id="cv-submit-btn" class="layui-btn layui-btn-fluid layui-btn-disabled" disabled="disabled" lay-submit="true" lay-filter="go">立即重置</button>
                    <input id="cv-ticket" type="hidden" name="ticket">
                    <input id="cv-rand" type="hidden" name="rand">
                </div>
            </div>
        </form>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('https://ssl.captcha.qq.com/TCaptcha.js',false) }}
    {{ js_include('web/js/captcha.verify.js') }}

{% endblock %}