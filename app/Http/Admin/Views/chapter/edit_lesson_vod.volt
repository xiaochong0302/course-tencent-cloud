{% set action_url = url({'for':'admin.chapter.content','id':chapter.id}) %}

{% if vod.file_id is defined %}
    {% set file_id = vod.file_id %}
{% else %}
    {% set file_id = '' %}
{% endif %}

<div class="layui-tab layui-tab-brief">
    <ul class="layui-tab-title kg-tab-title">
        <li class="layui-this">腾讯云点播</li>
        <li>外链云点播</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            {% if cos_play_urls %}
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
                    {% for item in cos_play_urls %}
                        <tr>
                            <td>{{ item.format }}</td>
                            <td>{{ item.duration|duration }}</td>
                            <td>{{ item.width }} x {{ item.height }}</td>
                            <td>{{ item.rate }}kbps</td>
                            <td>{{ item.size }}M</td>
                            <td>
                                <span class="layui-btn kg-preview" data-chapter-id="{{ chapter.id }}" data-play-url="{{ item.url|url_encode }}">预览</span>
                                <span class="layui-btn layui-btn-primary kg-copy" data-clipboard-text="{{ item.url }}">复制</span>
                            </td>
                        </tr>
                    {% endfor %}
                </table>
                <br>
            {% endif %}
            <form class="layui-form kg-form" id="vod-form" method="POST" action="{{ action_url }}">
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>上传视频</legend>
                </fieldset>
                <div class="layui-form-item" id="upload-block">
                    <label class="layui-form-label">视频文件</label>
                    <div class="layui-input-inline">
                        <input class="layui-input" type="text" name="file_id" value="{{ file_id }}" readonly="readonly" lay-verify="required">
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
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <button id="vod-submit" class="layui-btn layui-btn-disabled" disabled="disabled" lay-submit="true" lay-filter="go">提交</button>
                        <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="layui-tab-item">
            <form class="layui-form kg-form" method="POST" action="{{ action_url }}">
                <fieldset class="layui-elem-field layui-field-title">
                    <legend>外链视频</legend>
                </fieldset>
                <div class="layui-form-item">
                    <label class="layui-form-label">视频时长</label>
                    <div class="layui-input-block">
                        <div class="layui-inline">
                            <select name="file_remote[duration][hours]">
                                {% for value in 0..10 %}
                                    {% set selected = value == remote_duration.hours ? 'selected="selected"' : '' %}
                                    <option value="{{ value }}" {{ selected }}>{{ value }}小时</option>
                                {% endfor %}
                            </select>
                        </div>
                        <div class="layui-inline">
                            <select name="file_remote[duration][minutes]">
                                {% for value in 0..59 %}
                                    {% set selected = value == remote_duration.minutes ? 'selected="selected"' : '' %}
                                    <option value="{{ value }}" {{ selected }}>{{ value }}分钟</option>
                                {% endfor %}
                            </select>
                        </div>
                        <div class="layui-inline">
                            <select name="file_remote[duration][seconds]">
                                {% for value in 0..59 %}
                                    {% set selected = value == remote_duration.seconds ? 'selected="selected"' : '' %}
                                    <option value="{{ value }}" {{ selected }}>{{ value }}秒</option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">高清地址</label>
                    {% if remote_play_urls.hd.url %}
                        <div class="layui-inline" style="width:55%;">
                            <input id="tc-hd-url" class="layui-input" type="text" name="file_remote[hd][url]" value="{{ remote_play_urls.hd.url }}">
                        </div>
                        <div class="layui-inline">
                            <span class="layui-btn kg-preview" data-chapter-id="{{ chapter.id }}" data-play-url="{{ remote_play_urls.hd.url }}">预览</span>
                            <span class="layui-btn layui-btn-primary kg-copy" data-clipboard-target="#tc-hd-url">复制</span>
                        </div>
                    {% else %}
                        <div class="layui-inline" style="width:55%;">
                            <input id="tc-hd-url" class="layui-input" type="text" name="file_remote[hd][url]" value="">
                        </div>
                        <div class="layui-inline">
                            <span class="layui-btn layui-btn-disabled">预览</span>
                            <span class="layui-btn layui-btn-disabled">复制</span>
                        </div>
                    {% endif %}
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">标清地址</label>
                    {% if remote_play_urls.sd.url %}
                        <div class="layui-inline" style="width:55%;">
                            <input id="tc-sd-url" class="layui-input" type="text" name="file_remote[sd][url]" value="{{ remote_play_urls.sd.url }}">
                        </div>
                        <div class="layui-inline">
                            <span class="layui-btn kg-preview" data-chapter-id="{{ chapter.id }}" data-play-url="{{ remote_play_urls.sd.url }}">预览</span>
                            <span class="layui-btn layui-btn-primary kg-copy" data-clipboard-target="#tc-sd-url">复制</span>
                        </div>
                    {% else %}
                        <div class="layui-inline" style="width:55%;">
                            <input id="tc-sd-url" class="layui-input" type="text" name="file_remote[sd][url]" value="">
                        </div>
                        <div class="layui-inline">
                            <span class="layui-btn layui-btn-disabled">预览</span>
                            <span class="layui-btn layui-btn-disabled">复制</span>
                        </div>
                    {% endif %}
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">极速地址</label>
                    {% if remote_play_urls.fd.url %}
                        <div class="layui-inline" style="width:55%;">
                            <input id="tc-fd-url" class="layui-input" type="text" name="file_remote[fd][url]" value="{{ remote_play_urls.fd.url }}">
                        </div>
                        <div class="layui-inline">
                            <span class="layui-btn kg-preview" data-chapter-id="{{ chapter.id }}" data-play-url="{{ remote_play_urls.hd.url }}">预览</span>
                            <span class="layui-btn layui-btn-primary kg-copy" data-clipboard-target="#tc-fd-url">复制</span>
                        </div>
                    {% else %}
                        <div class="layui-inline" style="width:55%;">
                            <input id="tc-fd-url" class="layui-input" type="text" name="file_remote[fd][url]" value="">
                        </div>
                        <div class="layui-inline">
                            <span class="layui-btn layui-btn-disabled">预览</span>
                            <span class="layui-btn layui-btn-disabled">复制</span>
                        </div>
                    {% endif %}
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                        <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>