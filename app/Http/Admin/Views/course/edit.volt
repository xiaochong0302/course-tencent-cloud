{% extends 'templates/main.volt' %}

{% block content %}

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
                {{ partial('course/edit_related') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('https://cdn.jsdelivr.net/npm/vditor/dist/index.css', false) }}

{% endblock %}

{% block include_js %}

    {{ js_include('https://cdn.jsdelivr.net/npm/vditor/dist/index.min.js', false) }}
    {{ js_include('lib/xm-select.js') }}
    {{ js_include('admin/js/cover.upload.js') }}
    {{ js_include('admin/js/vditor.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        xmSelect.render({
            el: '#xm-category-ids',
            name: 'xm_category_ids',
            max: 5,
            filterable: true,
            filterMethod: function (val, item, index, prop) {
                return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
            },
            data: {{ xm_categories|json_encode }}
        });

        xmSelect.render({
            el: '#xm-teacher-ids',
            name: 'xm_teacher_ids',
            max: 5,
            filterable: true,
            filterMethod: function (val, item, index, prop) {
                return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
            },
            data: {{ xm_teachers|json_encode }}
        });

        xmSelect.render({
            el: '#xm-course-ids',
            name: 'xm_course_ids',
            max: 10,
            autoRow: true,
            filterable: true,
            filterMethod: function (val, item, index, prop) {
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

            $('.kg-submit').on('click', function () {

                var xm_category_ids = $('input[name=xm_category_ids]');
                var xm_teacher_ids = $('input[name=xm_teacher_ids]');

                if (xm_category_ids.val() === '') {
                    layer.msg('请选择分类', {icon: 2});
                    return false;
                }

                if (xm_teacher_ids.val() === '') {
                    layer.msg('请选择讲师', {icon: 2});
                    return false;
                }
            });

        });

    </script>

{% endblock %}