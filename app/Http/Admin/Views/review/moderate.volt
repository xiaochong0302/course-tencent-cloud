{% extends 'templates/main.volt' %}

{% block content %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>审核内容</legend>
    </fieldset>

    <form class="layui-form kg-form">
        <div class="layui-form-item">
            <label class="layui-form-label">内容实用</label>
            <div class="layui-input-block">
                <div id="rating1" class="kg-rating"></div>
                <input type="hidden" name="rating1" value="{{ review.rating1 }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">通俗易懂</label>
            <div class="layui-input-block">
                <div id="rating2" class="kg-rating"></div>
                <input type="hidden" name="rating2" value="{{ review.rating2 }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">逻辑清晰</label>
            <div class="layui-input-block">
                <div id="rating3" class="kg-rating"></div>
                <input type="hidden" name="rating3" value="{{ review.rating3 }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">评价内容</label>
            <div class="layui-input-block">
                <div class="layui-form-mid">{{ review.content }}</div>
            </div>
        </div>
    </form>

    <fieldset class="layui-elem-field layui-field-title">
        <legend>审核意见</legend>
    </fieldset>

    {% set moderate_url = url({'for':'admin.review.moderate','id':review.id}) %}

    <form class="layui-form kg-form kg-mod-form" method="POST" action="{{ moderate_url }}">
        <div class="layui-form-item">
            <label class="layui-form-label">审核</label>
            <div class="layui-input-block">
                <input type="radio" name="type" value="approve" title="通过">
                <input type="radio" name="type" value="reject" title="拒绝">
            </div>
        </div>
        <div id="reason-block" style="display:none;">
            <div class="layui-form-item">
                <label class="layui-form-label">理由</label>
                <div class="layui-input-block">
                    <select name="reason">
                        <option value="">请选择</option>
                        {% for reason in reasons %}
                            <option value="{{ reason }}">{{ reason }}</option>
                        {% endfor %}
                    </select>
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

        layui.use(['jquery', 'rate'], function () {

            var $ = layui.jquery;
            var rate = layui.rate;

            var $rating1 = $('input[name=rating1]');
            var $rating2 = $('input[name=rating2]');
            var $rating3 = $('input[name=rating3]');

            rate.render({
                elem: '#rating1',
                value: $rating1.val(),
                readonly: true,
            });

            rate.render({
                elem: '#rating2',
                value: $rating2.val(),
                readonly: true,
            });

            rate.render({
                elem: '#rating3',
                value: $rating3.val(),
                readonly: true,
            });

            form.on('radio(review)', function (data) {
                var block = $('#reason-block');
                if (data.value === 'approve') {
                    block.hide();
                } else {
                    block.show();
                }
            });

        });

    </script>

{% endblock %}