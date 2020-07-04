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
        autoplay: false,
        width: 760,
        height: 450
    };

    if (playUrls.od) {
        options.m3u8 = playUrls.od.url;
    }

    if (playUrls.hd) {
        options.m3u8_hd = playUrls.hd.url;
    }

    if (playUrls.sd) {
        options.m3u8_sd = playUrls.sd.url;
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