<form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.user.list'}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>搜索用户</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">编号</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="id" placeholder="编号精确匹配">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="name" placeholder="用户名模糊匹配">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">教学角色</label>
        <div class="layui-input-block">
            <input type="radio" name="edu_role" value="1" title="学员">
            <input type="radio" name="edu_role" value="2" title="讲师">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">后台角色</label>
        <div class="layui-input-block">
            {% for item in roles %}
                <input type="radio" name="admin_role" value="{{ item.id }}" title="{{ item.name }}">
            {% endfor %}
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">是否会员</label>
        <div class="layui-input-block">
            <input type="radio" name="vip" value="1" title="是">
            <input type="radio" name="vip" value="0" title="否">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">是否锁定</label>
        <div class="layui-input-block">
            <input type="radio" name="locked" value="1" title="是">
            <input type="radio" name="locked" value="0" title="否">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>

</form>