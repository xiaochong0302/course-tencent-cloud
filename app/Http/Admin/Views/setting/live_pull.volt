{% set pull_auth_display = live.pull_auth_enabled == 0 ? 'style="display:none;"' : '' %}
{% set ppt_display = live.pull_trans_enabled == 0 ? 'style="display:none;"' : '' %}

<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.live'}) }}">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>基础配置</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">拉流协议</label>
        <div class="layui-input-block">
            <input type="radio" name="pull_protocol" value="http" title="HTTP" {% if live.pull_protocol == "http" %}checked{% endif %}>
            <input type="radio" name="pull_protocol" value="https" title="HTTPS" {% if live.pull_protocol == "https" %}checked{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">拉流域名</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="pull_domain" value="{{ live.pull_domain }}" layui-verify="required">
        </div>
    </div>
    <fieldset class="layui-elem-field layui-field-title">
        <legend>鉴权配置</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">开启鉴权</label>
        <div class="layui-input-block">
            <input type="radio" name="pull_auth_enabled" value="1" title="是" lay-filter="pull_auth_enabled" {% if live.pull_auth_enabled == 1 %}checked{% endif %}>
            <input type="radio" name="pull_auth_enabled" value="0" title="否" lay-filter="pull_auth_enabled" {% if live.pull_auth_enabled == 0 %}checked{% endif %}>
        </div>
    </div>
    <div id="pull-auth-block" {{ pull_auth_display }}>
        <div class="layui-form-item">
            <label class="layui-form-label">鉴权密钥</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="pull_auth_key" value="{{ live.pull_auth_key }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">有效时间（秒）</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="pull_auth_delta" value="{{ live.pull_auth_delta }}">
            </div>
        </div>
    </div>
    <fieldset class="layui-elem-field layui-field-title">
        <legend>转码配置</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">开启转码</label>
        <div class="layui-input-block">
            <input type="radio" name="pull_trans_enabled" value="1" title="是" lay-filter="pull_trans_enabled" {% if live.pull_trans_enabled == 1 %}checked{% endif %}>
            <input type="radio" name="pull_trans_enabled" value="0" title="否" lay-filter="pull_trans_enabled" {% if live.pull_trans_enabled == 0 %}checked{% endif %}>
        </div>
    </div>
    <div id="ptt-block" {{ ppt_display }}>
        <table class="layui-table kg-table layui-form">
            <colgroup>
                <col>
                <col>
                <col>
                <col>
            </colgroup>
            <thead>
            <tr>
                <th>模板名称</th>
                <th>模板描述</th>
                <th>视频码率（kbps）</th>
                <th>视频高度（px）</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>fd</td>
                <td>流畅</td>
                <td>500</td>
                <td>540</td>
            </tr>
            <tr>
                <td>sd</td>
                <td>标清</td>
                <td>1000</td>
                <td>720</td>
            </tr>
            <tr>
                <td>hd</td>
                <td>高清</td>
                <td>2000</td>
                <td>1080</td>
            </tr>
            </tbody>
        </table>
        <br>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>
</form>

<form class="layui-form kg-form">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>拉流测试</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">Stream Name</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="stream_name" value="test" readonly="readonly">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button type="button" class="layui-btn" id="show-pull-test">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>
</form>