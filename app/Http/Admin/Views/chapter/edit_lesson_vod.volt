{% set action_url = url({'for':'admin.chapter.content','id':chapter.id}) %}
{% set transcode_url = url({'for':'admin.chapter.transcode','id':chapter.id}) %}
{% set vod_setting = setting('vod') %}

{% if vod.file_origin %}
    <fieldset class="layui-elem-field layui-field-title">
        <legend>视频原始信息</legend>
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
        <tr>
            <td>{{ vod.file_origin['format'] }}</td>
            <td>{{ vod.file_origin['duration']|duration }}</td>
            <td>{{ vod.file_origin['width'] }} x {{ vod.file_origin['height'] }}</td>
            <td>{{ vod.file_origin['rate'] }}kbps</td>
            <td>{{ vod.file_origin['size'] }}M</td>
            <td>
                <span class="layui-btn kg-preview" data-file-id="{{ vod.file_id }}" data-play-url="{{ vod.file_origin['url'] }}">预览</span>
            </td>
        </tr>
    </table>
    <br>
    <div class="kg-center">
        {% if vod_setting['std_trans_enabled'] == 1 and chapter.attrs['transcode']['standard']['status'] == 'pending' %}
            <button class="layui-btn kg-transcode" data-mode="standard" data-url="{{ transcode_url }}">标准转码</button>
        {% else %}
            <button class="layui-btn layui-btn-disabled">标准转码</button>
        {% endif %}
    </div>
{% endif %}

{% if vod.file_transcode %}
    <fieldset class="layui-elem-field layui-field-title">
        <legend>标准转码信息</legend>
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
        {% for item in vod.file_transcode %}
            <tr>
                <td>{{ item['format'] }}</td>
                <td>{{ item['duration']|duration }}</td>
                <td>{{ item['width'] }} x {{ item['height'] }}</td>
                <td>{{ item['rate'] }}kbps</td>
                <td>{{ item['size'] }}M</td>
                <td>
                    <span class="layui-btn kg-preview" data-file-id="{{ vod.file_id }}" data-play-url="{{ item['url'] }}">预览</span>
                </td>
            </tr>
        {% endfor %}
    </table>
    <br>
{% endif %}

<form class="layui-form kg-form">
    <div class="layui-form-item">
        <label class="layui-form-label">视频文件处理</label>
        <div class="layui-input-block">
            <input type="radio" name="cos_form" value="upload" title="上传本地文件" checked="checked" lay-filter="cos_form">
            <input type="radio" name="cos_form" value="remote" title="输入远程文件" lay-filter="cos_form">
        </div>
    </div>
</form>

<form class="layui-form kg-form" id="cos-upload-form" method="POST" action="{{ action_url }}">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>上传本地文件</legend>
    </fieldset>
    <div class="layui-form-item" id="upload-block">
        <label class="layui-form-label">视频文件</label>
        <div class="layui-input-inline">
            <input class="layui-input" type="text" value="{{ vod.file_id }}" readonly="readonly">
        </div>
        <div class="layui-inline">
            {% if vod.file_id > 0 %}
                <span class="layui-btn" id="upload-btn">重新上传</span>
            {% else %}
                <span class="layui-btn" id="upload-btn">选择视频</span>
            {% endif %}
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
    <div id="trans-block" style="display:none;">
        <div class="layui-form-item">
            <label class="layui-form-label">视频文件</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="text" name="file_id" value="{{ vod.file_id }}" readonly="readonly" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            {% set standard_disabled = vod_setting['std_trans_enabled'] == 0 ? 'disabled="disabled"' : '' %}
            <label class="layui-form-label">转码模式</label>
            <div class="layui-input-block">
                <input type="radio" name="trans_mode" value="standard" title="标准转码" {{ standard_disabled }}>
                <input type="radio" name="trans_mode" value="none" title="暂不转码" checked="checked">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button id="trans-submit" class="layui-btn layui-btn-disabled" disabled="disabled" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="section" value="cos">
        </div>
    </div>
</form>

<form class="layui-form kg-form" id="cos-remote-form" method="POST" action="{{ action_url }}" style="display:none;">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>输入远程文件</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">视频文件ID</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="file_id" placeholder="腾讯云视频文件ID，例如：387702293532778348" lay-verify="number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="section" value="cos">
        </div>
    </div>
</form>
