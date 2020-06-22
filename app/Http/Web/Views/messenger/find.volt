{% extends 'templates/layer.volt' %}

{% block content %}

    <div class="im-search">
        <form class="layui-form" action="{{ url({'for':'web.im.search'}) }}">
            <input class="layui-input" type="text" name="query" value="{{ request.get('query')|striptags }}" placeholder="请输入关键字...">
        </form>
    </div>

    <div class="tab-container">
        <div class="layui-tab layui-tab-brief user-tab">
            <ul class="layui-tab-title">
                <li class="layui-this">成员</li>
                <li>群组</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show" id="tab-users">
                    {{ partial('messenger/find_users',{'pager':users_pager}) }}
                </div>
                <div class="layui-tab-item" id="tab-groups">
                    {{ partial('messenger/find_groups',{'pager':groups_pager}) }}
                </div>
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/im.find.js') }}

{% endblock %}