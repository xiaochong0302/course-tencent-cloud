{% extends 'templates/layer.volt' %}

{% block content %}

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
    </form>

{% endblock %}