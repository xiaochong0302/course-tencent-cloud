{% extends 'templates/main.volt' %}

{% block content %}

    {% set list_pager_url = url({'for':'home.tag.list_pager'}) %}
    {% set my_pager_url = url({'for':'home.tag.my_pager'}) %}

    <div class="tab-wrap">
        <div class="layui-tabs user-tab" lay-filter="tag">
            <ul class="layui-tabs-header">
                <li class="layui-this">所有标签</li>
                <li>我的关注</li>
            </ul>
            <div class="layui-tabs-body">
                <div class="layui-tabs-item layui-show">
                    <div id="all-tag-list" data-url="{{ list_pager_url }}"></div>
                </div>
                <div class="layui-tabs-item">
                    {% if auth_user.id > 0 %}
                        <div id="my-tag-list" data-url="{{ my_pager_url }}"></div>
                    {% endif %}
                </div>
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/tag.list.js') }}

{% endblock %}
