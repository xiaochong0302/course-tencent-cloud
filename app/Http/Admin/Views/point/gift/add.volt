{% extends 'templates/main.volt' %}

{% block content %}

    <form id="form-1" class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.point_gift.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>添加礼品</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">礼品类型</label>
            <div class="layui-input-block">
                <input type="radio" name="type" value="1" title="课程" lay-filter="type">
                <input type="radio" name="type" value="2" title="商品" lay-filter="type">
            </div>
        </div>
        <div id="block-1" class="block" style="display:none;">
            <div class="layui-form-item">
                <label class="layui-form-label">课程编号</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="item_id" value="">
                </div>
            </div>
        </div>
        <div id="block-2" class="block" style="display:none;">
            <div class="layui-form-item">
                <label class="layui-form-label">商品名称</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="name" value="">
                </div>
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

            form.on('radio(type)', function (data) {
                var block = $('#block-' + data.value);
                $('.block').hide();
                block.show();
            });

        });

    </script>

{% endblock %}