{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.user.list'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>搜索用户</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">用户帐号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="user_id" placeholder="用户编号 / 手机号码 / 邮箱地址 精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户昵称</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="name" placeholder="用户昵称模糊匹配">
            </div>
        </div>
        <div class="layui-form-item" id="reg-time-range">
            <label class="layui-form-label">注册时间</label>
            <div class="layui-input-inline">
                <input class="layui-input" id="reg-start-time" type="text" name="create_time[]" autocomplete="off">
            </div>
            <div class="layui-form-mid"> -</div>
            <div class="layui-input-inline">
                <input class="layui-input" id="reg-end-time" type="text" name="create_time[]" autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item" id="active-time-range">
            <label class="layui-form-label">活跃时间</label>
            <div class="layui-input-inline">
                <input class="layui-input" id="active-start-time" type="text" name="active_time[]" autocomplete="off">
            </div>
            <div class="layui-form-mid"> -</div>
            <div class="layui-input-inline">
                <input class="layui-input" id="active-end-time" type="text" name="active_time[]" autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">教学角色</label>
            <div class="layui-input-block">
                {% for value,title in edu_role_types %}
                    <input type="checkbox" name="edu_role[]" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">后台角色</label>
            <div class="layui-input-block">
                {% for item in admin_roles %}
                    <input type="checkbox" name="admin_role[]" value="{{ item.id }}" title="{{ item.name }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否会员</label>
            <div class="layui-input-block">
                <input type="radio" name="vip" value="1" title="是">
                <input type="radio" name="vip" value="0" title="否">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否锁定</label>
            <div class="layui-input-block">
                <input type="radio" name="locked" value="1" title="是">
                <input type="radio" name="locked" value="0" title="否">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否删除</label>
            <div class="layui-input-block">
                <input type="radio" name="deleted" value="1" title="是">
                <input type="radio" name="deleted" value="0" title="否">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['laydate'], function () {

            var laydate = layui.laydate;

            laydate.render({
                elem: '#reg-time-range',
                type: 'datetime',
                range: ['#reg-start-time', '#reg-end-time'],
            });

            laydate.render({
                elem: '#active-time-range',
                type: 'datetime',
                range: ['#active-start-time', '#active-end-time'],
            });

        });

    </script>

{% endblock %}