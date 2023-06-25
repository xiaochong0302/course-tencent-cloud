{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">账号安全 - 修改手机</span>
                </div>
                <form class="layui-form security-form" method="POST" action="{{ url({'for':'home.account.update_phone'}) }}">
                    {% if account.password|length > 0 %}
                        <div class="layui-form-item">
                            <label class="layui-form-label">登录密码</label>
                            <div class="layui-input-block">
                                <input class="layui-input" type="password" name="login_password" autocomplete="off" placeholder="请输入当前登录密码" lay-verify="required">
                            </div>
                        </div>
                    {% endif %}
                    <div class="layui-form-item">
                        <label class="layui-form-label">手机号码</label>
                        <div class="layui-input-block">
                            <input id="cv-phone" class="layui-input" type="text" name="phone" placeholder="请输入新设手机号码" lay-verify="required|phone">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">验证码</label>
                        <div class="layui-input-inline verify-input-inline">
                            <input class="layui-input" type="text" name="verify_code" lay-verify="required">
                        </div>
                        <div class="layui-input-inline verify-btn-inline">
                            <button id="cv-phone-emit-btn" class="layui-btn layui-btn-disabled" type="button" disabled="disabled">获取验证码</button>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button id="cv-phone-submit-btn" class="layui-btn layui-btn-fluid layui-btn-disabled" disabled="disabled" lay-submit="true" lay-filter="go">提交修改</button>
                            <input id="cv-phone-captcha-ticket" type="hidden" name="captcha[ticket]">
                            <input id="cv-phone-captcha-rand" type="hidden" name="captcha[rand]">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/captcha.verify.phone.js') }}

{% endblock %}