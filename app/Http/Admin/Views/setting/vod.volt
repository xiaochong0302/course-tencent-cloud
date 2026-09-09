{% extends 'templates/main.volt' %}

{% block content %}

    {% set storage_region_display = vod.storage_type == 'fixed' ? 'display:block' : 'display:none' %}
    {% set std_trans_display = vod.std_trans_enabled == 1 ? 'display:block' : 'display:none' %}
    {% set encrypt_trans_display = vod.encrypt_trans_enabled == 1 ? 'display:block': 'display:none' %}
    {% set wmk_tpl_display = vod.wmk_enabled == 1 ? 'display:block' : 'display:none' %}
    {% set key_anti_display = vod.key_anti_enabled == 1 ? 'display:block': 'display:none' %}
    {% set video_quality = vod.video_quality|json_decode(true) %}

    <div class="layui-tabs">
        <ul class="layui-tabs-header">
            <li class="layui-this">存储设置</li>
            <li>转码设置</li>
            <li>防盗设置</li>
        </ul>
        <div class="layui-tabs-body">
            <div class="layui-tabs-item layui-show">
                {{ partial('setting/vod_storage') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('setting/vod_transcode') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('setting/vod_security') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form', 'colorpicker'], function () {

            var $ = layui.jquery;
            var form = layui.form;
            var colorpicker = layui.colorpicker;

            colorpicker.render({
                elem: '#rac-color-picker',
                color: $('#rac-color-input').val(),
                predefine: true,
                done: function (color) {
                    $('#rac-color-input').val(color);
                }
            });

            form.on('radio(storage_type)', function (data) {
                var block = $('#storage-region-block');
                if (data.value === 'fixed') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            form.on('radio(std_trans_enabled)', function (data) {
                var block = $('#standard-transcode-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            form.on('radio(encrypt_trans_enabled)', function (data) {
                var block = $('#encrypt-transcode-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            form.on('radio(wmk_enabled)', function (data) {
                var block = $('#wmk-tpl-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            form.on('radio(key_anti_enabled)', function (data) {
                var block = $('#key-anti-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

        });

    </script>

{% endblock %}
