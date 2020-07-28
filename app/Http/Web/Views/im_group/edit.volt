{% set update_group_url = url({'for':'web.im_group.update','id':group.id}) %}

<form class="layui-form" method="post" action="{{ update_group_url }}">
    <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="name" value="{{ group.name }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">简介</label>
        <div class="layui-input-block">
            <textarea class="layui-textarea" name="about" lay-verify="required">{{ group.about }}</textarea>
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