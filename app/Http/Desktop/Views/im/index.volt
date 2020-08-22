{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/group') }}
    {{ partial('macros/user') }}

    <div class="tab-wrap">
        <div class="layui-tab layui-tab-brief user-tab">
            <ul class="layui-tab-title">
                <li class="layui-this">新进群组</li>
                <li>新进用户</li>
                <li>活跃群组</li>
                <li>活跃用户</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    {{ partial('im/index_groups',{'groups':new_groups}) }}
                </div>
                <div class="layui-tab-item">
                    {{ partial('im/index_users',{'users':new_users}) }}
                </div>
                <div class="layui-tab-item">
                    {{ partial('im/index_groups',{'groups':active_groups}) }}
                </div>
                <div class="layui-tab-item">
                    {{ partial('im/index_users',{'users':active_users}) }}
                </div>
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('desktop/js/im.js') }}
    {{ js_include('desktop/js/im.apply.js') }}

{% endblock %}