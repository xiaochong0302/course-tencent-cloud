<!DOCTYPE html>
<html lang="zh-CN-Hans">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="{{ site_seo.getKeywords() }}">
    <meta name="description" content="{{ site_seo.getDescription() }}">
    <meta name="csrf-token" content="{{ csrfToken.getToken() }}">
    <title>{{ site_seo.getTitle() }}</title>
    {{ icon_link('favicon.ico') }}
    {{ css_link('lib/layui/css/layui.css') }}
    {{ css_link('web/css/common.css') }}
    {% block link_css %}{% endblock %}
    {% block inline_css %}{% endblock %}
</head>

{% set course_url = url({'for':'web.course.show','id':chapter.course.id}) %}

<body class="chapter-bg">
<div class="chapter-main">
    <div class="header clearfix">
        <div class="back fl">
            <span><i class="layui-icon layui-icon-return"></i> <a href="{{ course_url }}">返回课程主页</a></span>
        </div>
        <div class="stats fr">
            <span class="user">203</span>
            <span class="agree">300</span>
            <span class="oppose">50</span>
        </div>
        <div class="action fr">
            <span class="user">203</span>
            <span class="agree">300</span>
            <span class="oppose">50</span>
        </div>
    </div>
    <div class="content">
        {% block content %}{% endblock %}
    </div>
    <div class="chapter-sidebar-btn">
        <i class="switch-icon layui-icon layui-icon-shrink-right"></i>
    </div>
</div>
<div class="chapter-sidebar">
    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title">
            <li class="layui-this">目录</li>
            <li>讨论</li>
            <li>资料</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show" id="tab-courses" data-url="#"></div>
            <div class="layui-tab-item" id="tab-favorites" data-url="#"></div>
            <div class="layui-tab-item" id="tab-friends" data-url="#"></div>
        </div>
    </div>
</div>
{{ js_include('lib/layui/layui.all.js') }}
{{ js_include('web/js/common.js') }}
<script>
    var $ = layui.jquery;
    $('.chapter-sidebar-btn').on('click', function () {
        var switchIcon = $(this).children('.switch-icon');
        var mainBlock = $('.chapter-main');
        var sidebarBlock = $('.chapter-sidebar');
        var spreadLeft = 'layui-icon-spread-left';
        var shrinkRight = 'layui-icon-shrink-right';
        if (switchIcon.hasClass(spreadLeft)) {
            switchIcon.removeClass(spreadLeft).addClass(shrinkRight);
            mainBlock.css('right', 0);
            sidebarBlock.css('width', 0);
        } else {
            switchIcon.removeClass(shrinkRight).addClass(spreadLeft);
            mainBlock.css('right', 320);
            sidebarBlock.css('width', 320);
        }
    });
</script>

{% block include_js %}{% endblock %}
{% block inline_js %}{% endblock %}
</body>
</html>