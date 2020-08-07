<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.chapter.update','id':chapter.id}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ chapter.title }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">简介</label>
        <div class="layui-input-block">
            <textarea class="layui-textarea" name="summary">{{ chapter.summary }}</textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">排序</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="priority" value="{{ chapter.priority }}" lay-verify="number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">免费</label>
        <div class="layui-input-block">
            <input type="radio" name="free" value="1" title="是" {% if chapter.free == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="free" value="0" title="否" {% if chapter.free == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>
</form>