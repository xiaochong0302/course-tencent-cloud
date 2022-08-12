{% extends 'templates/main.volt' %}

{% block content %}

    {% set update_url = url({'for':'home.uc.update_contact'}) %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">收货信息</span>
                </div>
                <form class="layui-form profile-form" method="post" action="{{ update_url }}">
                    <div class="layui-form-item">
                        <label class="layui-form-label">真实姓名</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="text" name="name" value="{{ contact.name }}" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">手机号码</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="text" name="phone" value="{{ contact.phone }}" lay-verify="phone">
                        </div>
                    </div>
                    <div class="layui-form-item" id="area-picker" style="margin-bottom: 25px;">
                        <div class="layui-form-label">所在地区</div>
                        <div class="layui-input-inline" style="width: 200px;">
                            <select name="address[province]" class="province-selector" data-value="{{ contact.add_province }}" lay-verify="required">
                                <option value="">请选择省</option>
                            </select>
                        </div>
                        <div class="layui-input-inline" style="width: 200px;">
                            <select name="address[city]" class="city-selector" data-value="{{ contact.add_city }}" lay-verify="required">
                                <option value="">请选择市</option>
                            </select>
                        </div>
                        <div class="layui-input-inline" style="width: 200px;">
                            <select name="address[county]" class="county-selector" data-value="{{ contact.add_county }}">
                                <option value="">请选择区</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">详细地址</label>
                        <div class="layui-input-block">
                            <textarea class="layui-textarea" name="address[other]" lay-verify="required">{{ contact.add_other }}</textarea>
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

    {{ js_include('home/js/user.console.contact.js') }}

{% endblock %}