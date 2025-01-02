{% extends 'templates/main.volt' %}

{% block content %}

    {% set update_url = url({'for':'admin.topic.update','id':topic.id}) %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑专题</legend>
    </fieldset>

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">基本信息</li>
            <li>相关课程</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('topic/edit_basic') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('topic/edit_course') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/xm-select.js') }}
    {{ js_include('admin/js/cover.upload.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'layer'], function () {

            xmSelect.render({
                el: '#xm-course-ids',
                name: 'xm_course_ids',
                max: 15,
                autoRow: true,
                filterable: true,
                filterMethod: function (val, item) {
                    return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
                },
                data: {{ xm_courses|json_encode }}
            });

        });

    </script>

{% endblock %}
