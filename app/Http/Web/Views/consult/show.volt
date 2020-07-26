{% extends 'templates/layer.volt' %}

{% block content %}

    {% set rating_url = url({'for':'web.consult.rating','id':consult.id}) %}
    {% set answer = consult.answer ? consult.answer : '<span class="gray">稍安勿燥，请耐心等待回复吧</span>' %}

    <form class="layui-form review-form">
        <div class="layui-form-item mb0">
            <label class="layui-form-label">课程：</label>
            <div class="layui-form-mid">{{ consult.course.title }}</div>
        </div>
        <div class="layui-form-item mb0">
            <label class="layui-form-label">章节：</label>
            <div class="layui-form-mid">{{ consult.chapter.title }}</div>
        </div>
        <div class="layui-form-item mb0">
            <label class="layui-form-label">提问：</label>
            <div class="layui-form-mid">{{ consult.question }}</div>
        </div>
        <div class="layui-form-item mb0">
            <label class="layui-form-label">回复：</label>
            <div class="layui-form-mid">{{ answer }}</div>
        </div>
        {% if consult.answer %}
            <div class="layui-form-item">
                <label class="layui-form-label">评分：</label>
                <div class="layui-input-block">
                    <input type="hidden" name="rating" value="{{ consult.rating }}">
                    <input type="hidden" name="rating_url" value="{{ rating_url }}">
                    <div id="rating"></div>
                </div>
            </div>
        {% endif %}
    </form>

{% endblock %}

{% block inline_js %}

    <script>
        layui.use(['jquery', 'layer', 'rate'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var rate = layui.rate;
            var rating = $('input[name=rating]').val();
            var ratingUrl = $('input[name=rating_url]').val();
            rate.render({
                elem: '#rating',
                value: rating,
                choose: function (value) {
                    $.post(ratingUrl, {rating: value}, function () {
                        layer.msg('评价成功');
                    });
                }
            });
        });
    </script>

{% endblock %}