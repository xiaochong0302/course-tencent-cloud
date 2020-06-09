{% extends 'templates/full.volt' %}

{% block content %}

    <div class="layout-main">
        <div class="layout-sidebar">{{ partial('my/menu') }}</div>
        <div class="layout-content">
            <div class="container">
                <div class="my-nav-title">个人信息</div>
                <form class="layui-form my-form" method="post" action="{{ url({'for':'web.my.update_profile'}) }}">
                    <div class="layui-form-item">
                        <label class="layui-form-label">昵称</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="text" name="name" value="{{ user.name }}" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">性别</label>
                        <div class="layui-input-block">
                            {% set male_checked = user.gender == 1 ? 'checked' : '' %}
                            {% set female_checked = user.gender == 2 ? 'checked' : '' %}
                            {% set none_checked = user.gender == 3 ? 'checked' : '' %}
                            <input type="radio" name="gender" value="1" title="男" {{ male_checked }}>
                            <input type="radio" name="gender" value="2" title="女" {{ female_checked }}>
                            <input type="radio" name="gender" value="3" title="保密" {{ none_checked }}>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">简介</label>
                        <div class="layui-input-block">
                            <textarea class="layui-textarea" name="about" lay-verify="required">{{ user.about }}</textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                            <button class="layui-btn layui-btn-primary" type="reset">重置</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
{% endblock %}