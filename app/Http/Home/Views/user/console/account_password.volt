{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">账号安全 - 修改密码</span>
                </div>
                <form class="layui-form security-form" method="POST" action="{{ url({'for':'home.account.update_pwd'}) }}">
                    <div class="layui-form-item">
                        <label class="layui-form-label">原始密码</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="password" name="origin_password" autocomplete="off" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">新设密码</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="password" name="new_password" placeholder="字母数字特殊字符6-16位" autocomplete="off" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">确认密码</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="password" name="confirm_password" placeholder="字母数字特殊字符6-16位" autocomplete="off" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                            <button class="layui-btn layui-btn-primary" type="reset">重置</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

{% endblock %}

