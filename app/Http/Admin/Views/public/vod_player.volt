{% extends 'templates/main.volt' %}

{% block content %}

    <div id="player"></div>

    <div class="layui-hide">
        <input type="hidden" name="play_url" value="{{ play_url }}">
    </div>

{% endblock %}

{% block inline_css %}

    <style>
        .kg-body {
            padding: 0;
        }
    </style>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/tc-player-2.4.0.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery'], function () {

            var $ = layui.jquery;

            var playUrl = $('input[name=play_url]').val();

            new TcPlayer('player', {
                m3u8: playUrl,
                autoplay: false,
                width: 720,
                height: 405
            });

        });

    </script>

{% endblock %}