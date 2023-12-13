layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var interval = null;
    var intervalTime = 15000;
    var userId = window.user.id;
    var requestId = helper.getRequestId();
    var planId = $('input[name="chapter.me.plan_id"]').val();
    var learningUrl = $('input[name="chapter.learning_url"]').val();
    var playUrls = JSON.parse($('input[name="chapter.play_urls"]').val());

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

    var player = new DPlayer({
        container: document.getElementById('player'),
        live: true,
        video: {
            quality: quality,
            defaultQuality: 0,
        }
    });

    player.on('play', function () {
        start();
    });

    player.on('pause', function () {
        stop();
    });

    player.on('ended', function () {
        stop();
    });

    /**
     * 播放器中央播放按钮
     */
    var $playMask = $('#play-mask');

    $playMask.on('click', function () {
        $(this).hide();
        player.toggle();
    });

    function start() {
        $playMask.hide();
        if (interval != null) {
            clearInterval(interval);
            interval = null;
        }
        interval = setInterval(learning, intervalTime);
    }

    function stop() {
        $playMask.show();
        if (interval != null) {
            clearInterval(interval);
            interval = null;
        }
    }

    function learning() {
        if (userId !== '0' && planId !== '0') {
            $.ajax({
                type: 'POST',
                url: learningUrl,
                data: {
                    plan_id: planId,
                    request_id: requestId,
                    interval_time: intervalTime,
                }
            });
        }
    }

});