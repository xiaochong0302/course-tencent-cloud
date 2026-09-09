{% extends 'templates/main.volt' %}

{% block content %}

    {% set update_url = url({'for':'admin.course.update','id':course.id}) %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑课程</legend>
    </fieldset>

    <div class="layui-tabs">
        <ul class="layui-tabs-header">
            <li class="layui-this">基本信息</li>
            <li>课程介绍</li>
            <li>搜索优化</li>
            <li>营销设置</li>
            <li>相关课程</li>
        </ul>
        <div class="layui-tabs-body">
            <div class="layui-tabs-item layui-show">
                {{ partial('course/edit_basic') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('course/edit_desc') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('course/edit_seo') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('course/edit_sale') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('course/edit_related') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('lib/vditor/dist/index.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('lib/xm-select.js') }}
    {{ js_include('lib/vditor/dist/index.min.js') }}
    {{ js_include('admin/js/content.vditor.js') }}
    {{ js_include('admin/js/cover.upload.js') }}

{% endblock %}

{% block inline_js %}

    <script>

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

{% endblock %}
