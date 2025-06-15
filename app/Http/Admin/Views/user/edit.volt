{% extends 'templates/main.volt' %}

{% block content %}

    {% set lock_expiry_display = user.locked == 1 ? 'display:block': 'display:none' %}
    {% set vip_expiry_display = user.vip == 1 ? 'display:block': 'display:none' %}
    {% set update_user_url = url({'for':'admin.user.update','id':user.id}) %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑用户</legend>
    </fieldset>

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">基本信息</li>
            <li>账号信息</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <form class="layui-form kg-form" method="POST" action="{{ update_user_url }}">
                    <div class="layui-form-item">
                        <label class="layui-form-label" style="padding-top:30px;">头像</label>
                        <div class="layui-input-inline" style="width:80px;">
                            <img id="img-avatar" class="kg-avatar" src="{{ user.avatar }}">
                            <input type="hidden" name="avatar" value="{{ user.avatar }}">
                        </div>
                        <div class="layui-input-inline" style="padding-top:25px;">
                            <button id="change-avatar" class="layui-btn layui-btn-sm" type="button">更换</button>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">昵称</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="text" name="name" value="{{ user.name }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">头衔</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="text" name="title" value="{{ user.title }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">简介</label>
                        <div class="layui-input-block">
                            <textarea class="layui-textarea" name="about">{{ user.about }}</textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">教学角色</label>
                        <div class="layui-input-block">
                            <input type="radio" name="edu_role" value="1" title="学员" {% if user.edu_role == 1 %}checked="checked"{% endif %}>
                            <input type="radio" name="edu_role" value="2" title="讲师" {% if user.edu_role == 2 %}checked="checked"{% endif %}>
                        </div>
                    </div>
                    {% if auth_user.admin_role == 1 %}
                        <div class="layui-form-item">
                            <label class="layui-form-label">后台角色</label>
                            <div class="layui-input-block">
                                <input type="radio" name="admin_role" value="0" title="无" {% if user.admin_role == 0 %}checked="checked"{% endif %}>
                                {% for role in admin_roles %}
                                    {% if role.id > 1 %}
                                        <input type="radio" name="admin_role" value="{{ role.id }}" title="{{ role.name }}" {% if user.admin_role == role.id %}checked="checked"{% endif %}>
                                    {% endif %}
                                {% endfor %}
                            </div>
                        </div>
                    {% endif %}
                    <div class="layui-form-item">
                        <label class="layui-form-label">会员特权</label>
                        <div class="layui-input-block">
                            <input type="radio" name="vip" value="1" title="是" lay-filter="vip" {% if user.vip == 1 %}checked="checked"{% endif %}>
                            <input type="radio" name="vip" value="0" title="否" lay-filter="vip" {% if user.vip == 0 %}checked="checked"{% endif %}>
                        </div>
                    </div>
                    <div id="vip-expiry-block" style="{{ vip_expiry_display }}">
                        <div class="layui-form-item">
                            <label class="layui-form-label">会员期限</label>
                            <div class="layui-input-block">
                                {% if user.vip_expiry_time > 0 %}
                                    <input class="layui-input" type="text" name="vip_expiry_time" autocomplete="off" value="{{ date('Y-m-d H:i:s',user.vip_expiry_time) }}">
                                {% else %}
                                    <input class="layui-input" type="text" name="vip_expiry_time" autocomplete="off">
                                {% endif %}
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">锁定帐号</label>
                        <div class="layui-input-block">
                            <input type="radio" name="locked" value="1" title="是" lay-filter="locked" {% if user.locked == 1 %}checked="checked"{% endif %}>
                            <input type="radio" name="locked" value="0" title="否" lay-filter="locked" {% if user.locked == 0 %}checked="checked"{% endif %}>
                        </div>
                    </div>
                    <div id="lock-expiry-block" style="{{ lock_expiry_display }}">
                        <div class="layui-form-item">
                            <label class="layui-form-label">锁定期限</label>
                            <div class="layui-input-block">
                                {% if user.lock_expiry_time > 0 %}
                                    <input class="layui-input" type="text" name="lock_expiry_time" autocomplete="off" value="{{ date('Y-m-d H:i:s',user.lock_expiry_time) }}">
                                {% else %}
                                    <input class="layui-input" type="text" name="lock_expiry_time" autocomplete="off">
                                {% endif %}
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
                            <input type="hidden" name="type" value="user">
                        </div>
                    </div>
                </form>
            </div>
            <div class="layui-tab-item">
                <form class="layui-form kg-form" method="POST" action="{{ update_user_url }}">
                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>编辑帐号</legend>
                    </fieldset>
                    <div class="layui-form-item">
                        <label class="layui-form-label">手机</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="text" name="phone" value="{{ account.phone }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">邮箱</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="text" name="email" value="{{ account.email }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">密码</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="text" name="password" placeholder="不修改密码请留空">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
                            <input type="hidden" name="type" value="account">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('admin/js/avatar.upload.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form', 'laydate'], function () {

            var $ = layui.jquery;
            var form = layui.form;
            var laydate = layui.laydate;

            laydate.render({
                elem: 'input[name=vip_expiry_time]',
                type: 'datetime'
            });

            laydate.render({
                elem: 'input[name=lock_expiry_time]',
                type: 'datetime'
            });

            form.on('radio(vip)', function (data) {
                var block = $('#vip-expiry-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            form.on('radio(locked)', function (data) {
                var block = $('#lock-expiry-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

        });

    </script>

{% endblock %}
