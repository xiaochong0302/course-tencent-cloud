{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.slide.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>添加轮播</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">目标类型</label>
            <div class="layui-input-block">
                {% for value,title in target_types %}
                    <input type="radio" name="target" value="{{ value }}" title="{{ title }}" lay-filter="target">
                {% endfor %}
            </div>
        </div>
        <div id="block-1" class="block" style="display:none;">
            <div class="layui-form-item">
                <label class="layui-form-label">课程选择</label>
                <div class="layui-input-block">
                    <div id="xm-course-id"></div>
                </div>
            </div>
        </div>
        <div id="block-2" class="block" style="display:none;">
            <div class="layui-form-item">
                <label class="layui-form-label">单页选择</label>
                <div class="layui-input-block">
                    <div id="xm-page-id"></div>
                </div>
            </div>
        </div>
        <div id="block-3" class="block" style="display:none;">
            <div class="layui-form-item">
                <label class="layui-form-label">链接地址</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="url">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">轮播标题</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="title" lay-verify="required">
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

{% block include_js %}

    {{ js_include('lib/xm-select.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form'], function () {

            var $ = layui.jquery;
            var form = layui.form;

            xmSelect.render({
                el: '#xm-course-id',
                name: 'xm_course_id',
                radio: true,
                filterable: true,
                filterMethod: function (val, item) {
                    return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
                },
                data: {{ xm_courses|json_encode }}
            });

            xmSelect.render({
                el: '#xm-page-id',
                name: 'xm_page_id',
                radio: true,
                filterable: true,
                filterMethod: function (val, item) {
                    return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
                },
                data: {{ xm_pages|json_encode }}
            });

            form.on('radio(target)', function (data) {
                $('.block').hide();
                $('#block-' + data.value).show();
            });

        });

    </script>

{% endblock %}
