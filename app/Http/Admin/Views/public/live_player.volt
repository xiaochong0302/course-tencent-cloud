{% extends 'templates/main.volt' %}

{% block content %}

    <div id="player"></div>

    <div class="layui-hide">
        <textarea id="play_urls">{{ pull_urls|json_encode }}</textarea>
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

    {{ js_include('lib/dplayer/flv.min.js') }}
    {{ js_include('lib/dplayer/DPlayer.min.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery'], function () {

            var $ = layui.jquery;

            var playUrls = JSON.parse($('#play_urls').val());

            var formats = ['flv'];

            var rates = [
                {name: 'od', label: '原画'},
                {name: 'hd', label: '高清'},
                {name: 'sd', label: '标清'},
                {name: 'fd', label: '极速'},
            ];

            var quality = [];

            $.each(formats, function (i, format) {
                $.each(rates, function (k, rate) {
                    if (playUrls.hasOwnProperty(format) && playUrls[format].hasOwnProperty(rate.name)) {
                        quality.push({
                            name: rate.label,
                            url: playUrls[format][rate.name],
                            type: 'flv',
                        });
                    }
                });
            });

            new DPlayer({
                container: document.getElementById('player'),
                live: true,
                video: {
                    quality: quality,
                    defaultQuality: 0,
                }
            });

        });

    </script>

{% endblock %}