<form class="layui-form kg-form" method="POST" action="{{ update_url }}">
    <div class="layui-form-item">
        <label class="layui-form-label">关键字</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="keywords" value="{{ article.keywords }}" placeholder="多个关键字用逗号分隔">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">内容摘要</label>
        <div class="layui-input-block">
            <textarea class="layui-textarea" name="summary">{{ article.summary }}</textarea>
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