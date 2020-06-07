{% extends 'templates/full.volt' %}

{% block content %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a href="{{ url({'for':'web.my.account'}) }}">帐号安全</a>
        <a><cite>修改密码</cite></a>
    </div>

    <div class="account-container container">
        <form class="layui-form account-form" method="POST" action="{{ url({'for':'web.account.update_pwd'}) }}">
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input class="layui-input" type="password" name="origin_password" placeholder="原始密码" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input class="layui-input" type="password" name="new_password" placeholder="新设密码" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input class="layui-input" type="password" name="confirm_password" placeholder="确认密码" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn layui-btn-fluid" lay-submit="true" lay-filter="go">提交</button>
                </div>
            </div>
        </form>
    </div>

{% endblock %}