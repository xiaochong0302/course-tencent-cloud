{% extends 'templates/main.volt' %}

{% block content %}

    {% set group.about = group.about ? group.about : '这个家伙真懒，什么都没有留下~' %}
    {% set users_url = url({'for':'web.im_group.users','id':group.id}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a href="{{ url({'for':'web.im_group.list'}) }}">群组</a>
            <a><cite>{{ group.name }}</cite></a>
        </span>
    </div>

    <div class="layout-main">
        <div class="layout-content">
            <div class="layui-card group-about">
                <div class="layui-card-header">小组介绍</div>
                <div class="layui-card-body">
                    <blockquote class="layui-elem-quote">{{ group.about }}</blockquote>
                </div>
            </div>
            <div class="layui-card group-about">
                <div class="layui-card-header">小组成员</div>
                <div class="layui-card-body">
                    <div id="group-user-list" data-url="{{ users_url }}"></div>
                </div>
            </div>
        </div>
        <div class="layout-sidebar">
            {{ partial('im_group/show_owner') }}
            {{ partial('im_group/show_active_users') }}
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/user.show.js') }}

{% endblock %}