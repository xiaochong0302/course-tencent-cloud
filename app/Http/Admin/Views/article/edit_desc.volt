<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.article.update','id':article.id}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">详情</label>
        <div class="layui-input-block">
            <div id="vditor"></div>
            <textarea name="content" class="layui-hide" id="vditor-textarea">{{ article.content }}</textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">简介</label>
        <div class="layui-input-block">
            <textarea name="summary" class="layui-textarea">{{ article.summary }}</textarea>
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