{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro content_title(model) %}
        {% if model == 1 %}
            点播信息
        {% elseif model == 3 %}
            图文信息
        {% endif %}
    {%- endmacro %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑课时</legend>
    </fieldset>

    <div class="layui-tabs">
        <ul class="layui-tabs-header">
            <li class="layui-this">基本信息</li>
            <li>{{ content_title(chapter.model) }}</li>
            <li>搜索优化</li>
        </ul>
        <div class="layui-tabs-body">
            <div class="layui-tabs-item layui-show">
                {{ partial('chapter/edit_lesson_basic') }}
            </div>
            <div class="layui-tabs-item">
                {% if chapter.model == 1 %}
                    {{ partial('chapter/edit_lesson_vod') }}
                {% elseif chapter.model == 3 %}
                    {{ partial('chapter/edit_lesson_read') }}
                {% endif %}
            </div>
            <div class="layui-tabs-item">
                {{ partial('chapter/edit_lesson_seo') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block link_css %}

    {% if chapter.model == 3 %}

        {{ css_link('lib/vditor/dist/index.css') }}

    {% endif %}

{% endblock %}

{% block include_js %}

    {% if chapter.model == 1 %}

        {{ js_include('lib/vod-js-sdk-v6.min.js') }}
        {{ js_include('lib/clipboard.min.js') }}
        {{ js_include('admin/js/media.upload.js') }}
        {{ js_include('admin/js/media.preview.js') }}

    {% elseif chapter.model == 3 %}

        {{ js_include('lib/vditor/dist/index.min.js') }}
        {{ js_include('admin/js/content.vditor.js') }}

    {% endif %}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form', 'layer', 'laydate'], function () {

            var $ = layui.jquery;
            var form = layui.form;
            var layer = layui.layer;
            var laydate = layui.laydate;

            laydate.render({
                elem: 'input[name=start_time]',
                type: 'datetime'
            });

            laydate.render({
                elem: 'input[name=end_time]',
                type: 'datetime'
            });

            form.on('radio(cos_form)', function (data) {
                var $cosUploadForm = $('#cos-upload-form');
                var $cosRemoteForm = $('#cos-remote-form');
                if (data.value === 'upload') {
                    $cosUploadForm.show();
                    $cosRemoteForm.hide();
                } else {
                    $cosRemoteForm.show();
                    $cosUploadForm.hide();
                }
            });

            $('.kg-transcode').on('click', function () {
                var url = $(this).data('url');
                var mode = $(this).data('mode');
                var $this = $(this);
                layer.confirm('确定要执行转码吗？', function () {
                    $.post(url, {mode: mode},
                        function (res) {
                            layer.msg(res.msg, {icon: 1});
                            $this.off('click').addClass('layui-btn-disabled');
                        });
                });
            });

        });

    </script>

{% endblock %}
