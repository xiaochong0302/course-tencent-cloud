{% extends 'templates/main.volt' %}

{% block content %}

    {% set update_url = url({'for':'admin.course.update','id':course.id}) %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑课程</legend>
    </fieldset>

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">基本信息</li>
            {% if course.model == 4 %}
                <li>面授信息</li>
            {% endif %}
            <li>课程介绍</li>
            <li>营销设置</li>
            <li>课件资料</li>
            <li>相关课程</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('course/edit_basic') }}
            </div>
            {% if course.model == 4 %}
                <div class="layui-tab-item">
                    {{ partial('course/edit_offline') }}
                </div>
            {% endif %}
            <div class="layui-tab-item">
                {{ partial('course/edit_desc') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('course/edit_sale') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('course/edit_resource') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('course/edit_related') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/xm-select.js') }}
    {{ js_include('lib/cos-js-sdk-v5.min.js') }}
    {{ js_include('lib/kindeditor/kindeditor.min.js') }}
    {{ js_include('admin/js/content.editor.js') }}
    {{ js_include('admin/js/cover.upload.js') }}
    {{ js_include('admin/js/course.resource.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        xmSelect.render({
            el: '#xm-tag-ids',
            name: 'xm_tag_ids',
            max: 5,
            filterable: true,
            filterMethod: function (val, item) {
                return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
            },
            data: {{ xm_tags|json_encode }}
        });

        xmSelect.render({
            el: '#xm-course-ids',
            name: 'xm_course_ids',
            max: 10,
            autoRow: true,
            filterable: true,
            filterMethod: function (val, item) {
                return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
            },
            data: {{ xm_courses|json_encode }}
        });

    </script>

    <script>

        layui.use(['jquery', 'form', 'layer', 'laydate'], function () {

            var $ = layui.jquery;
            var layer = layui.layer;
            var laydate = layui.laydate;

            laydate.render({
                elem: 'input[name="attrs[start_date]"]',
                type: 'date'
            });

            laydate.render({
                elem: 'input[name="attrs[end_date]"]',
                type: 'date'
            });

        });

    </script>

{% endblock %}
