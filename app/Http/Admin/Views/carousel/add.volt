{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.slide.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>添加轮播</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="title" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">目标类型</label>
            <div class="layui-input-block">
                <input type="radio" name="target" value="1" title="课程" lay-filter="target" checked="checked">
                <input type="radio" name="target" value="2" title="单页" lay-filter="target">
                <input type="radio" name="target" value="3" title="链接" lay-filter="target">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" id="target-label">课程编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="content" lay-verify="required">
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

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form'], function () {

            var $ = layui.jquery;
            var form = layui.form;

            var targetLabels = [
                '课程编号',
                '单页编号',
                '链接地址',
            ];

            var targetLabelBlock = $('#target-label');

            form.on('radio(target)', function (data) {
                var index = data.value - 1;
                targetLabelBlock.html(targetLabels[index]);
            });

            targetLabelBlock.html(targetLabels[0]);

        });

    </script>

{% endblock %}