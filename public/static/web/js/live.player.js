layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var interval = null;
    var intervalTime = 5000;
    var position = 0;
    var userId = window.koogua.user.id;
    var chapterId = $('input[name="chapter.id"]').val();
    var planId = $('input[name="chapter.plan_id"]').val();
    var learningUrl = $('input[name="chapter.learning_url"]').val();
    var playUrls = JSON.parse($('input[name="chapter.play_urls"]').val());
    var requestId = helper.getRequestId();

    var options = {
        live: true,
        autoplay: true,
        h5_flv: true,
        width: 760,
        height: 428
    };

    if (playUrls.rtmp && playUrls.rtmp.od) {
        options.rtmp = playUrls.rtmp.od;
    }

    if (playUrls.rtmp && playUrls.rtmp.hd) {
        options.rtmp_hd = playUrls.rtmp.hd;
    }

    if (playUrls.rtmp && playUrls.rtmp.sd) {
        options.rtmp_sd = playUrls.rtmp.sd;
    }

    if (playUrls.flv && playUrls.flv.od) {
        options.flv = playUrls.flv.od;
    }

    if (playUrls.flv && playUrls.flv.hd) {
        options.flv_hd = playUrls.flv.hd;
    }

    if (playUrls.flv && playUrls.flv.sd) {
        options.flv_sd = playUrls.flv.sd;
    }

    if (playUrls.m3u8 && playUrls.m3u8.od) {
        options.m3u8 = playUrls.m3u8.od;
    }

    if (playUrls.m3u8 && playUrls.m3u8.hd) {
        options.m3u8_hd = playUrls.m3u8.hd;
    }

    if (playUrls.m3u8 && playUrls.m3u8.sd) {
        options.m3u8_sd = playUrls.m3u8.sd;
    }

    if (userId !== '0' && planId !== '0') {
        options.listener = function (msg) {
            if (msg.type === 'play') {
                start();
            } else if (msg.type === 'pause') {
                stop();
            } else if (msg.type === 'end') {
                stop();
            }
        }
    }

    var player = new TcPlayer('player', options);

    if (position > 0) {
        player.currentTime(position);
    }

    function start() {
        if (interval != null) {
            clearInterval(interval);
            interval = null;
        }
        interval = setInterval(learning, intervalTime);
    }

    function stop() {
        clearInterval(interval);
        interval = null;
    }

    function learning() {
        $.ajax({
            type: 'POST',
            url: learningUrl,
            data: {
                request_id: requestId,
                chapter_id: chapterId,
                plan_id: planId,
                interval: intervalTime,
                position: player.currentTime(),
            }
        });
    }

});