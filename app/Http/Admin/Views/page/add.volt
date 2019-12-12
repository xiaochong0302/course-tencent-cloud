<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.page.create'}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>添加单页</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" lay-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-block">
            <textarea name="content" class="layui-hide" id="kg-layedit"></textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">发布</label>
        <div class="layui-input-block">
            <input type="radio" name="published" value="1" title="是" checked="checked">
            <input type="radio" name="published" value="0" title="否">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="kg-submit layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>

</form>

{{ partial('partials/layedit') }}