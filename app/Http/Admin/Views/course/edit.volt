{% extends 'templates/main.volt' %}

{% block content %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑课程</legend>
    </fieldset>

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">基本信息</li>
            <li>课程介绍</li>
            <li>营销设置</li>
            <li>相关课程</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('course/edit_basic') }}
            </div>
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

{% block include_js %}

    {{ js_include('lib/xm-select.js') }}
    {{ js_include('admin/js/xm-course.js') }}
    {{ js_include('admin/js/cover.upload.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        xmSelect.render({
            el: '#xm-category-ids',
            name: 'xm_category_ids',
            max: 5,
            prop: {
                name: 'name',
                value: 'id'
            },
            data: {{ xm_categories|json_encode }}
        });

        xmSelect.render({
            el: '#xm-teacher-ids',
            name: 'xm_teacher_ids',
            paging: true,
            max: 5,
            prop: {
                name: 'name',
                value: 'id'
            },
            data: {{ xm_teachers|json_encode }}
        });

        xmCourse({{ xm_courses|json_encode }}, '/admin/xm/course/all');

    </script>

    <script>

        layui.use(['jquery', 'form', 'layer'], function () {

            var $ = layui.jquery;
            var form = layui.form;
            var layer = layui.layer;

            form.on('radio(price_mode)', function (data) {
                var priceBlock = $('#price-block');
                if (data.value === 'free') {
                    priceBlock.hide();
                } else {
                    priceBlock.show();
                }
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