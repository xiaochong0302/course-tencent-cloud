{% set file_id = vod ? vod.file_id : '' %}

{% if play_urls %}
    <fieldset class="layui-elem-field layui-field-title">
        <legend>视频信息</legend>
    </fieldset>
    <table class="kg-table layui-table">
        <tr>
            <th>格式</th>
            <th>时长</th>
            <th>分辨率</th>
            <th>码率</th>
            <th>大小</th>
            <th width="16%">操作</th>
        </tr>
        {% for item in play_urls %}
            <tr>
                <td>{{ item.format }}</td>
                <td>{{ item.duration|duration }}</td>
                <td>{{ item.width }} x {{ item.height }}</td>
                <td>{{ item.rate }}kbps</td>
                <td>{{ item.size }}M</td>
                <td>
                    <span class="layui-btn layui-btn-sm kg-preview" data-chapter-id="{{ chapter.id }}" data-play-url="{{ item.url|url_encode }}">预览</span>
                    <span class="layui-btn layui-btn-sm kg-copy" data-clipboard-text="{{ item.url }}">复制</span>
                </td>
            </tr>
        {% endfor %}
    </table>
    <br>
{% endif %}

<fieldset class="layui-elem-field layui-field-title">
    <legend>上传视频</legend>
</fieldset>

<form class="layui-form kg-form" id="vod-form" method="POST" action="{{ url({'for':'admin.chapter.content','id':chapter.id}) }}">
    <div class="layui-form-item" id="upload-block">
        <label class="layui-form-label">视频文件</label>
        <div class="layui-input-block">
            <span class="layui-btn" id="upload-btn">选择视频</span>
            <input class="layui-hide" type="file" name="file" accept="video/*,audio/*">
        </div>
    </div>
    <div class="layui-form-item layui-hide" id="upload-progress-block">
        <label class="layui-form-label">上传进度</label>
        <div class="layui-input-block">
            <div class="layui-progress layui-progress-big" lay-showpercent="yes" lay-filter="upload-progress" style="top:10px;">
                <div class="layui-progress-bar" lay-percent="0%"></div>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">文件编号</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="file_id" value="{{ file_id }}" readonly="readonly" lay-verify="required">
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