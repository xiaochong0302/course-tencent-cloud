<form class="layui-form kg-form" method="POST" action="{{ update_url }}">
    <div class="layui-form-item">
        <div class="layui-input-block" style="margin:0;">
            <div id="vditor"></div>
            <textarea name="profile" class="layui-hide" id="vditor-textarea">{{ user.profile }}</textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block" style="margin:0;">
            <button class="kg-submit layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="type" value="user">
        </div>
    </div>
</form>
