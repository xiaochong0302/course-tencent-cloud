{% extends 'templates/full.volt' %}

{% block content %}

    {% set learning_url = url({'for':'web.chapter.learning','id':chapter.id}) %}
    {% set danmu_url = url({'for':'web.chapter.danmu','id':chapter.id}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a><cite>{{ chapter.course.title }}</cite></a>
            <a><cite>{{ chapter.title }}</cite></a>
        </span>
    </div>

    <div class="layout-main clearfix">
        <div class="layout-content">
            <div class="player-wrap wrap">
                <div id="player"></div>
                <div id="danmu"></div>
            </div>
            <div class="danmu-action-wrap wrap">
                <form class="layui-form" lay-filter="danmu.form" action="{{ url({'for':'web.danmu.create'}) }}">
                    <div class="layui-input-inline" style="width: 50px;">
                        <a href="javascript:" class="layui-icon layui-icon-set icon-danmu-set"></a>
                    </div>
                    <div class="layui-input-inline" style="width: 655px;">
                        {% if auth_user.id > 0 %}
                            <input class="layui-input" type="text" name="danmu.text" maxlength="50" placeholder="快来发个弹幕吧" lay-verType="tips" lay-verify="required">
                        {% else %}
                            <input class="layui-input" type="text" name="danmu.text" placeholder="登录后才可以发送弹幕哦" readonly="readonly">
                        {% endif %}
                        <button class="layui-hide" type="submit" lay-submit="true" lay-filter="danmu.send">发送</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="layout-sidebar">
            {{ partial('chapter/menu') }}
        </div>
    </div>

    <div id="my-danmu-set" style="display:none;">
        <form class="layui-form" lay-filter="danmu.form.set" style="padding:20px 0;">
            <div class="layui-form-item">
                <label class="layui-form-label">显示弹幕</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="danmu.status" lay-filter="danmu.status" lay-skin="switch" lay-text="是|否" checked="checked">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">透明度</label>
                <div class="layui-input-block">
                    <input type="radio" name="danmu.opacity" lay-filter="danmu.opacity" value="1" title="0" checked="checked">
                    <input type="radio" name="danmu.opacity" lay-filter="danmu.opacity" value="0.75" title="25%">
                    <input type="radio" name="danmu.opacity" lay-filter="danmu.opacity" value="0.5" title="50%">
                    <input type="radio" name="danmu.opacity" lay-filter="danmu.opacity" value="0.25" title="75%">
                    <input type="radio" name="danmu.opacity" lay-filter="danmu.opacity" value="0" title="100%">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">颜色</label>
                <div class="layui-input-block">
                    <input type="radio" name="danmu.color" value="white" title="白" checked="checked">
                    <input type="radio" name="danmu.color" value="red" title="红">
                    <input type="radio" name="danmu.color" value="orange" title="黄">
                    <input type="radio" name="danmu.color" value="blue" title="蓝">
                    <input type="radio" name="danmu.color" value="green" title="绿">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">位置</label>
                <div class="layui-input-block">
                    <input type="radio" name="danmu.position" value="0" title="滚动" checked="checked">
                    <input type="radio" name="danmu.position" value="1" title="顶部">
                    <input type="radio" name="danmu.position" value="2" title="底部">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">字号</label>
                <div class="layui-input-block">
                    <input type="radio" name="danmu.size" value="0" title="小" checked="checked">
                    <input type="radio" name="danmu.size" value="1" title="大">
                </div>
            </div>
        </form>
    </div>

    <div class="layui-hide">
        <input type="hidden" name="chapter.id" value="{{ chapter.id }}">
        <input type="hidden" name="chapter.position" value="{{ chapter.me.position }}">
        <input type="hidden" name="chapter.plan_id" value="{{ chapter.me.plan_id }}">
        <input type="hidden" name="chapter.learning_url" value="{{ learning_url }}">
        <input type="hidden" name="chapter.danmu_url" value="{{ danmu_url }}">
        <input type="hidden" name="chapter.play_urls" value='{{ chapter.play_urls|json_encode }}'>
    </div>

{% endblock %}

{% block include_js %}

    <script src="//imgcache.qq.com/open/qcloud/video/vcplayer/TcPlayer-2.3.2.js"></script>

    {{ js_include('lib/jquery.min.js') }}
    {{ js_include('lib/jquery.danmu.min.js') }}
    {{ js_include('web/js/vod.player.js') }}

{% endblock %}