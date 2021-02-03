{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.package.update','id':package.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>编辑套餐</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="title" value="{{ package.title }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">简介</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" name="summary">{{ package.summary }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">相关课程</label>
            <div class="layui-input-block">
                <div id="xm-course-ids"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">优惠价格</label>
                <div class="layui-input-inline">
                    <input class="layui-input" type="text" name="market_price" value="{{ package.market_price }}" lay-verify="number">
                </div>
                <div class="layui-form-mid layui-word-aux">元</div>
                <div class="layui-form-mid">
                    <a class="kg-guiding" href="javascript:" package-id="{{ package.id }}">（价格参考）</a>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">会员价格</label>
                <div class="layui-input-inline">
                    <input class="layui-input" type="text" name="vip_price" value="{{ package.vip_price }}" lay-verify="number">
                </div>
                <div class="layui-form-mid layui-word-aux">元</div>
                <div class="layui-form-mid">
                    <a class="kg-guiding" href="javascript:" package-id="{{ package.id }}">（价格参考）</a>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="kg-submit layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/xm-select.js') }}
    {{ js_include('admin/js/xm-course.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        xmCourse({{ xm_courses|json_encode }}, '/admin/xm/course/paid');

        layui.use(['jquery', 'layer'], function () {

            var $ = layui.jquery;
            var layer = layui.layer;

            $('.kg-guiding').on('click', function () {
                var xmCourseIds = $('input[name=xm_course_ids]').val();
                var url = '/admin/package/guiding?xm_course_ids=' + xmCourseIds;
                layer.open({
                    id: 'xm-course',
                    type: 2,
                    title: '价格参考',
                    resize: false,
                    area: ['720px', '400px'],
                    content: [url]
                });
            });

        });

    </script>

{% endblock %}