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

{% block include_js %}

    {{ js_include('lib/tc-player-2.4.0.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery'], function () {

            var $ = layui.jquery;

            var options = {
                live: true,
                autoplay: true,
                h5_flv: true,
                width: 720,
                height: 405
            };

            var playUrls = JSON.parse('{{ pull_urls|json_encode }}');
            var formats = ['rtmp', 'flv', 'm3u8'];
            var rates = ['od', 'hd', 'sd'];

            $.each(formats, function (i, format) {
                $.each(rates, function (k, rate) {
                    if (playUrls.hasOwnProperty(format) && playUrls[format].hasOwnProperty(rate)) {
                        var key = k === 0 ? format : format + '_' + rate;
                        options[key] = playUrls[format][rate];
                    }
                });
            });

            new TcPlayer('player', options);

        });

    </script>

{% endblock %}