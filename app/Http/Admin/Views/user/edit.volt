{% extends 'templates/main.volt' %}

{% block content %}

    {% set vip_expiry_time = user.vip_expiry_time > 0 ? date('Y-m-d H:i:s',user.vip_expiry_time) : '' %}
    {% set lock_expiry_time = user.lock_expiry_time > 0 ? date('Y-m-d H:i:s',user.lock_expiry_time) : '' %}
    {% set lock_expiry_display = user.locked == 1 ? 'display:block': 'display:none' %}
    {% set vip_expiry_display = user.vip == 1 ? 'display:block': 'display:none' %}
    {% set update_user_url = url({'for':'admin.user.update','id':user.id}) %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑用户</legend>
    </fieldset>

    <div class="layui-tabs">
        <ul class="layui-tabs-header">
            <li class="layui-this">基本信息</li>
            <li>账号信息</li>
            <li>个人资料</li>
        </ul>
        <div class="layui-tabs-body">
            <div class="layui-tabs-item layui-show">
                {{ partial('user/edit_basic') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('user/edit_account') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('user/edit_profile') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('lib/vditor/dist/index.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('lib/vditor/dist/index.min.js') }}
    {{ js_include('admin/js/content.vditor.js') }}
    {{ js_include('admin/js/avatar.upload.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form', 'laydate'], function () {

            var $ = layui.jquery;
            var form = layui.form;
            var laydate = layui.laydate;

            laydate.render({
                elem: 'input[name=vip_expiry_time]',
                type: 'datetime'
            });

            laydate.render({
                elem: 'input[name=lock_expiry_time]',
                type: 'datetime'
            });

            form.on('radio(edu_role)', function (data) {
                var block = $('#profile-block');
                if (data.value === '2') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            form.on('radio(vip)', function (data) {
                var block = $('#vip-expiry-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            form.on('radio(locked)', function (data) {
                var block = $('#lock-expiry-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

        });

    </script>

{% endblock %}
