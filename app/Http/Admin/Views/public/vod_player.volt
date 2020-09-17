{% extends 'templates/main.volt' %}

{% block content %}

    <div id="player"></div>

{% endblock %}

{% block inline_css %}

    <style>
        .kg-body {
            padding: 0;
        }
    </style>

{% endblock %}

{% block inline_js %}

    <script src="https://imgcache.qq.com/open/qcloud/video/vcplayer/TcPlayer-2.3.3.js"></script>

    <script>

        layui.use(['jquery'], function () {

            new TcPlayer('player', {
                m3u8: '{{ play_url }}',
                autoplay: false,
                width: 720,
                height: 405
            });

        });

    </script>

{% endblock %}