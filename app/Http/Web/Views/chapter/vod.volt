{% extends 'templates/full.volt' %}

{% block content %}

    {% set chapter_full_url = full_url({'for':'web.chapter.show','id':chapter.id}) %}
    {% set course_url = url({'for':'web.course.show','id':chapter.course.id}) %}
    {% set learning_url = url({'for':'web.chapter.learning','id':chapter.id}) %}
    {% set danmu_url = url({'for':'web.chapter.danmu','id':chapter.id}) %}
    {% set like_url = url({'for':'web.chapter.like','id':chapter.id}) %}
    {% set qrcode_url = url({'for':'web.qrcode_img'},{'text':chapter_full_url}) %}
    {% set consult_url = url({'for':'web.consult.add'},{'chapter_id':chapter.id}) %}
    {% set liked_class = chapter.me.liked ? 'active' : '' %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="{{ course_url }}"><i class="layui-icon layui-icon-return"></i> 返回课程</a>
            <a><cite>{{ chapter.course.title }}</cite></a>
            <a><cite>{{ chapter.title }}</cite></a>
        </span>
        <span class="share">
            <a href="javascript:" title="学习人次"><i class="layui-icon layui-icon-user"></i><em>{{ chapter.user_count }}</em></a>
            <a href="javascript:" title="我要点赞" data-url="{{ like_url }}"><i class="layui-icon layui-icon-praise icon-praise {{ liked_class }}"></i><em class="like-count">{{ chapter.like_count }}</em></a>
            <a href="javascript:" title="我要提问" data-url="{{ consult_url }}"><i class="layui-icon layui-icon-help icon-help"></i><em>{{ chapter.consult_count }}</em></a>
            <a href="javascript:" title="分享到微信"><i class="layui-icon layui-icon-login-wechat icon-wechat"></i></a>
            <a href="javascript:" title="分享到QQ空间"><i class="layui-icon layui-icon-login-qq icon-qq"></i></a>
            <a href="javascript:" title="分享到微博"><i class="layui-icon layui-icon-login-weibo icon-weibo"></i></a>
        </span>
    </div>

    <div class="layout-main clearfix">
        <div class="layout-content">
            <div class="player-wrap wrap">
                <div id="player"></div>
                <div id="danmu"></div>
                <form class="layui-form danmu-form" lay-filter="danmu.form" action="{{ url({'for':'web.danmu.create'}) }}">
                    {% if auth_user.id > 0 %}
                        <a href="javascript:" title="弹幕设置"><i class="layui-icon layui-icon-set icon-danmu-set"></i></a>
                        <input class="layui-input" type="text" name="danmu.text" autocomplete="off" maxlength="50" placeholder="快来发个弹幕吧" lay-verType="tips" lay-verify="required">
                    {% else %}
                        <input class="layui-input" type="text" name="danmu.text" placeholder="登录后才可以发送弹幕哦" readonly="readonly">
                    {% endif %}
                    <button class="layui-hide" type="submit" lay-submit="true" lay-filter="danmu.send">发送</button>
                </form>
            </div>
        </div>
        <div class="layout-sidebar">
            {{ partial('chapter/contents') }}
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

    <div class="layui-hide">
        <input type="hidden" name="share.title" value="{{ chapter.course.title }}">
        <input type="hidden" name="share.pic" value="{{ chapter.course.cover }}">
        <input type="hidden" name="share.url" value="{{ chapter_full_url }}">
        <input type="hidden" name="share.qrcode" value="{{ qrcode_url }}">
    </div>

{% endblock %}

{% block include_js %}

    <script src="//imgcache.qq.com/open/qcloud/video/vcplayer/TcPlayer-2.3.3.js"></script>

    {{ js_include('lib/jquery.min.js') }}
    {{ js_include('lib/jquery.danmu.min.js') }}
    {{ js_include('web/js/course.share.js') }}
    {{ js_include('web/js/chapter.like.js') }}
    {{ js_include('web/js/chapter.vod.js') }}
    {{ js_include('web/js/chapter.vod.player.js') }}

{% endblock %}