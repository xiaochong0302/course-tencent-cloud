{% set res_list_url = url({'for':'admin.chapter.resources','id':chapter.id}) %}

<fieldset class="layui-elem-field layui-field-title">
    <legend>资料列表</legend>
</fieldset>

<div id="res-list" data-url="{{ res_list_url }}"></div>

<fieldset class="layui-elem-field layui-field-title">
    <legend>上传资料</legend>
</fieldset>

<form class="layui-form kg-form" id="res-form">
    <div class="layui-form-item" id="res-upload-block">
        <label class="layui-form-label">资源文件</label>
        <div class="layui-input-block">
            <span class="layui-btn" id="res-upload-btn">选择文件</span>
            <input class="layui-hide" type="file" name="res_file" accept="*/*">
        </div>
    </div>
    <div class="layui-form-item layui-hide" id="res-progress-block">
        <label class="layui-form-label">上传进度</label>
        <div class="layui-input-block">
            <div class="layui-progress layui-progress-big" lay-showpercent="yes" lay-filter="res-upload-progress" style="top:10px;">
                <div class="layui-progress-bar" lay-percent="0%"></div>
            </div>
        </div>
    </div>
    <div class="layui-hide">
        <input type="hidden" name="chapter_id" value="{{ chapter.id }}">
        <input type="hidden" name="bucket" value="{{ cos.bucket }}">
        <input type="hidden" name="region" value="{{ cos.region }}">
    </div>
</form>