{% extends 'templates/layer.volt' %}

{% block content %}

    <form class="layui-form account-form" method="POST" action="{{ url({'for':'desktop.account.update_pwd'}) }}">
        <br>
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

{% endblock %}