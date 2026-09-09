<form class="layui-form kg-form" method="POST" action="{{ update_url }}">
    <div class="layui-form-item">
        <div class="layui-input-block" style="margin:0;">
            <div id="vditor"></div>
            <textarea name="details" class="layui-hide" id="vditor-textarea">{{ course.details }}</textarea>
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
