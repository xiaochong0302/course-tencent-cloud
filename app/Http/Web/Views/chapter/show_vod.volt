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
            <div class="player-container container">
                <div id="player"></div>
                <div id="danmu"></div>
            </div>
            <div class="danmu-action container">
                <form class="layui-form" action="{{ url({'for':'web.danmu.create'}) }}">
                    <div class="layui-input-inline" style="width: 100px;">
                        <input type="checkbox" name="status" title="弹幕" checked="checked" lay-filter="danmu.status">
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