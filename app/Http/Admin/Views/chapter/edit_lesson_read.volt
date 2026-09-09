<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.chapter.content','id':chapter.id}) }}">
    <div class="layui-form-item">
        <div class="layui-input-block" style="margin:0;">
            <input type="radio" name="settings[comment_enabled]" value="1" title="开启评论" {% if read.settings['comment_enabled'] == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="settings[comment_enabled]" value="0" title="关闭评论" {% if read.settings['comment_enabled'] == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block" style="margin:0;">
            <div id="vditor"></div>
            <textarea name="content" class="layui-hide" id="vditor-textarea">{{ read.content }}</textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block kg-center" style="margin:0;">
            <button class="kg-submit layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>
</form>
