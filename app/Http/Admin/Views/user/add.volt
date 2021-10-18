{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.user.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>添加用户</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">手机</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="phone" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">密码</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="password" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">教学角色</label>
            <div class="layui-input-block">
                <input type="radio" name="edu_role" value="1" title="学员" checked="checked">
                <input type="radio" name="edu_role" value="2" title="讲师">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">后台角色</label>
            <div class="layui-input-block">
                <input type="radio" name="admin_role" value="0" title="无" checked="checked">
                {% if auth_user.admin_role == 1 %}
                    {% for role in admin_roles %}
                        {% if role.id > 1 %}
                            <input type="radio" name="admin_role" value="{{ role.id }}" title="{{ role.name }}">
                        {% endif %}
                    {% endfor %}
                {% endif %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

{% endblock %}
