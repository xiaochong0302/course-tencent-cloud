<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.question.update','id':question.id}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ question.title }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">标签</label>
        <div class="layui-input-block">
            <div id="xm-tag-ids"></div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">关键字</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="keywords" value="{{ question.keywords }}" placeholder="多个关键字用逗号分隔">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">匿名</label>
        <div class="layui-input-block">
            <input type="radio" name="anonymous" value="1" title="是" {% if question.anonymous == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="anonymous" value="0" title="否" {% if question.anonymous == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">关闭</label>
        <div class="layui-input-block">
            <input type="radio" name="closed" value="1" title="是" {% if question.closed == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="closed" value="0" title="否" {% if question.closed == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn kg-submit" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>
</form>