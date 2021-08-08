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

        #player {
            width: 720px;
            height: 405px;
        }
    </style>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/dplayer/hls.min.js') }}
    {{ js_include('lib/dplayer/DPlayer.min.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery'], function () {

            var $ = layui.jquery;

            var playUrl = $('input[name=play_url]').val();

            new DPlayer({
                container: document.getElementById('player'),
                video: {url: playUrl},
            });

        });

    </script>

{% endblock %}