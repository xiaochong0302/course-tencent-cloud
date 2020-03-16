<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.consult.update','id':consult.id}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑咨询</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">提问</label>
        <div class="layui-input-block">
            <textarea name="question" class="layui-textarea" readonly="readonly" lay-verify="required">{{ consult.question }}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">回复</label>
        <div class="layui-input-block">
            <textarea name="answer" class="layui-textarea" lay-verify="required">{{ consult.answer }}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">发布</label>
        <div class="layui-input-block">
            <input type="radio" name="published" value="1" title="是" {% if consult.published == 1 %}checked="true"{% endif %}>
            <input type="radio" name="published" value="0" title="否" {% if consult.published == 0 %}checked="true"{% endif %}>
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