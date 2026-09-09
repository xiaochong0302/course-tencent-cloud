{% extends 'templates/main.volt' %}

{% block content %}

    {% set update_url = url({'for':'admin.course.update_user','id':course_user.id}) %}
    {% set expiry_time = course_user.expiry_time > 0 ? date('Y-m-d H:i:s',course_user.expiry_time) : '' %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑学员</legend>
    </fieldset>

    <form class="layui-form kg-form" method="POST" action="{{ update_url }}">
        <div class="layui-form-item">
            <label class="layui-form-label">课程名称</label>
            <div class="layui-input-block">
                <div class="layui-form-mid">{{ course.title }}</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户昵称</label>
            <div class="layui-input-block">
                <div class="layui-form-mid">{{ user.name }}（{{ user.id }}）</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">过期时间</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="expiry_time" value="{{ expiry_time }}" lay-verify="required">
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

{% block inline_js %}

    <script>

        layui.use(['laydate'], function () {

            var laydate = layui.laydate;

            laydate.render({
                elem: 'input[name=expiry_time]',
                type: 'datetime'
            });

        });

    </script>

{% endblock %}
