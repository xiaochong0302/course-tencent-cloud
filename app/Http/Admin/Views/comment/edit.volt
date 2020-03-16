<fieldset class="layui-elem-field layui-field-title">
    <legend>编辑评论</legend>
</fieldset>

<form class="layui-form" method="POST" action="{{ url({'for':'admin.comment.update','id':comment.id}) }}">

    <div class="layui-form-item">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-block">
            <textarea name="content" lay-verify="required" class="layui-textarea">{{ comment.content }}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">开启</label>
        <div class="layui-input-block">
            <input type="radio" name="published" value="1" title="是" {% if comment.published == 1 %}checked="true"{% endif %}>
            <input type="radio" name="published" value="0" title="否" {% if comment.published == 0 %}checked="true"{% endif %}>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="go">提交</button>
            <button type="reset" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>

</form>