{% extends 'templates/main.volt' %}

{% block content %}

    {% set target = request.get('target','string','search') %}
    {% set count = request.get('count','int',-1) %}

    {% if target == 'search' %}
        {% set action_url = url({'for':'admin.course.users','id':course.id}) %}
        {% set title = '搜索学员' %}
    {% else %}
        {% set action_url = url({'for':'admin.course.export_user','id':course.id}) %}
        {% set title = '导出学员' %}
    {% endif %}

    <form class="layui-form kg-form" method="GET" action="{{ action_url }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ title }}</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">所属课程</label>
            <div class="layui-input-block">
                <div class="layui-form-mid">{{ course.title }}</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户账号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="user_id" placeholder="用户编号 / 手机号码 / 邮箱地址">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">加入方式</label>
            <div class="layui-input-block">
                {% for value,title in source_types %}
                    <input type="checkbox" name="source_type[]" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item" id="create-time-range">
            <label class="layui-form-label">加入时间</label>
            <div class="layui-input-inline">
                <input class="layui-input" id="create-start-time" type="text" name="create_time[]">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline">
                <input class="layui-input" id="create-end-time" type="text" name="create_time[]">
            </div>
        </div>
        <div class="layui-form-item" id="expiry-time-range">
            <label class="layui-form-label">过期时间</label>
            <div class="layui-input-inline">
                <input class="layui-input" id="expiry-start-time" type="text" name="expiry_time[]">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline">
                <input class="layui-input" id="expiry-end-time" type="text" name="expiry_time[]">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="search">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
                <input type="hidden" name="target" value="{{ target }}">
                <input type="hidden" name="count" value="{{ count }}">
            </div>
        </div>
    </form>

{% endblock %}

{% block include_js %}

    {{ js_include('admin/js/export.search.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['laydate'], function () {

            var laydate = layui.laydate;

            laydate.render({
                elem: '#create-time-range',
                type: 'datetime',
                range: ['#create-start-time', '#create-end-time'],
            });

            laydate.render({
                elem: '#expiry-time-range',
                type: 'datetime',
                range: ['#expiry-start-time', '#expiry-end-time'],
            });

        });

    </script>

{% endblock %}
