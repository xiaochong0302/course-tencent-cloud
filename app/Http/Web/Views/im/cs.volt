{% extends 'templates/layer.volt' %}

{% block content %}

    <div class="layui-hide">
        <input type="hidden" name="cs_user.id" value="{{ cs_user.id }}">
        <input type="hidden" name="cs_user.name" value="{{ cs_user.name }}">
        <input type="hidden" name="cs_user.avatar" value="{{ cs_user.avatar }}">
        <input type="hidden" name="cs_user.welcome" value="欢迎到访在线客服，有什么可以帮助您的？">
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/im.cs.js') }}

{% endblock %}