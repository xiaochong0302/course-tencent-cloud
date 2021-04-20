{% extends 'templates/layer.volt' %}

{% block content %}
    <form class="layui-form" method="post" action="{{ url({'for':'home.comment.create_reply','id':comment.id}) }}">
        <div class="layui-form-item">
            <textarea class="layui-textarea" name="content" placeholder="@{{ comment.owner.name }}" lay-verify="required"></textarea>
        </div>
        <div class="layui-form-item center">
            <button class="layui-btn" lay-submit="true" lay-filter="replyComment">提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </form>
{% endblock %}

{% block include_js %}

    {{ js_include('home/js/comment.js') }}

{% endblock %}