<form class="layui-form kg-form" method="POST" action="{{ update_url }}">
    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ page.title }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">别名</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="alias" value="{{ page.alias }}" placeholder="可以通过 /page/{别名} 访问页面">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">发布</label>
        <div class="layui-input-block">
            <input type="radio" name="published" value="1" title="是" {% if page.published == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="published" value="0" title="否" {% if page.published == 0 %}checked="checked"{% endif %}>
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
