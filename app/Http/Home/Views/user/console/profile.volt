{% extends 'templates/main.volt' %}

{% block content %}

    {% set update_url = url({'for':'home.uc.update_profile'}) %}
    {% set gender_male_checked = user.gender == 1 ? 'checked' : '' %}
    {% set gender_female_checked = user.gender == 2 ? 'checked' : '' %}
    {% set gender_none_checked = user.gender == 3 ? 'checked' : '' %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">个人信息</span>
                </div>
                <form class="layui-form profile-form" method="post" action="{{ update_url }}">
                    <div class="layui-form-item">
                        <label class="layui-form-label">头像</label>
                        <div class="layui-input-inline" style="width: 110px;">
                            <img id="img-avatar" class="my-avatar" src="{{ user.avatar }}">
                            <input type="hidden" name="avatar" value="{{ user.avatar }}">
                        </div>
                        <div class="layui-input-inline" style="padding-top:35px;">
                            <button id="change-avatar" class="layui-btn layui-btn-sm" type="button">更换</button>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">昵称</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="text" name="name" value="{{ user.name }}" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">性别</label>
                        <div class="layui-input-block">
                            <input type="radio" name="gender" value="1" title="男" {{ gender_male_checked }}>
                            <input type="radio" name="gender" value="2" title="女" {{ gender_female_checked }}>
                            <input type="radio" name="gender" value="3" title="保密" {{ gender_none_checked }}>
                        </div>
                    </div>
                    <div class="layui-form-item" id="area-picker" style="margin-bottom: 25px;">
                        <div class="layui-form-label">所在地</div>
                        <div class="layui-input-inline" style="width: 200px;">
                            <select name="area[province]" class="province-selector" data-value="{{ user.area.province }}" lay-verify="required">
                                <option value="">请选择省</option>
                            </select>
                        </div>
                        <div class="layui-input-inline" style="width: 200px;">
                            <select name="area[city]" class="city-selector" data-value="{{ user.area.city }}" lay-verify="required">
                                <option value="">请选择市</option>
                            </select>
                        </div>
                        <div class="layui-input-inline" style="width: 200px;">
                            <select name="area[county]" class="county-selector" data-value="{{ user.area.county }}">
                                <option value="">请选择区</option>
                            </select>
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

{% block include_js %}

    {{ js_include('home/js/user.console.profile.js') }}

{% endblock %}
