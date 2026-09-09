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
            <input class="layui-input" type="text" name="about" value="{{ user.about }}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">教学角色</label>
        <div class="layui-input-block">
            <input type="radio" name="edu_role" value="1" title="学员" lay-filter="edu_role" {% if user.edu_role == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="edu_role" value="2" title="讲师" lay-filter="edu_role" {% if user.edu_role == 2 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div id="profile-block" style="{{ profile_display }}">
        <div class="layui-form-item">
            <label class="layui-form-label">个人资料</label>
            <div class="layui-input-block">
                <button id="btn-profile" class="layui-btn layui-btn-sm" type="button" data-url="{{ update_profile_url }}">编辑</button>
            </div>
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
                <input class="layui-input" type="text" name="vip_expiry_time" value="{{ vip_expiry_time }}">
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
                <input class="layui-input" type="text" name="lock_expiry_time" value="{{ lock_expiry_time }}">
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
