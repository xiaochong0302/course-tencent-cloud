{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.review.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>添加评价</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">所属课程</label>
            <div class="layui-input-block">
                <div id="xm-course-id"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">内容实用</label>
            <div class="layui-input-block">
                <div id="rating1" class="kg-rating"></div>
                <input type="hidden" name="rating1" value="5">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">通俗易懂</label>
            <div class="layui-input-block">
                <div id="rating2" class="kg-rating"></div>
                <input type="hidden" name="rating2" value="5">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">逻辑清晰</label>
            <div class="layui-input-block">
                <div id="rating3" class="kg-rating"></div>
                <input type="hidden" name="rating3" value="5">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">课程评价</label>
            <div class="layui-input-block">
                <textarea name="content" class="layui-textarea" lay-verify="required"></textarea>
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

        layui.use(['jquery', 'rate'], function () {

            var $ = layui.jquery;
            var rate = layui.rate;

            var $rating1 = $('input[name=rating1]');
            var $rating2 = $('input[name=rating2]');
            var $rating3 = $('input[name=rating3]');

            xmSelect.render({
                el: '#xm-course-id',
                name: 'course_id',
                filterable: true,
                radio: true,
                filterMethod: function (val, item) {
                    return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
                },
                data: {{ xm_courses|json_encode }}
            });

            rate.render({
                elem: '#rating1',
                value: $rating1.val(),
                choose: function (value) {
                    $rating1.val(value);
                }
            });

            rate.render({
                elem: '#rating2',
                value: $rating2.val(),
                choose: function (value) {
                    $rating2.val(value);
                }
            });

            rate.render({
                elem: '#rating3',
                value: $rating3.val(),
                choose: function (value) {
                    $rating3.val(value);
                }
            });

        });

    </script>

{% endblock %}
