layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var interval = null;
    var intervalTime = 15000;
    var userId = window.user.id;
    var requestId = helper.getRequestId();
    var planId = $('input[name="chapter.plan_id"]').val();
    var lastPosition = $('input[name="chapter.position"]').val();
    var learningUrl = $('input[name="chapter.learning_url"]').val();
    var playUrls = JSON.parse($('input[name="chapter.play_urls"]').val());

    var options = {
        autoplay: false,
        width: 760,
        height: 428,
    };

    if (playUrls.hasOwnProperty('od')) {
        options.m3u8 = playUrls.od.url;
    }

    if (playUrls.hasOwnProperty('hd')) {
        options.m3u8_hd = playUrls.hd.url;
    }

    if (playUrls.hasOwnProperty('sd')) {
        options.m3u8_sd = playUrls.sd.url;
    }

    options.listener = function (msg) {
        if (msg.type === 'play') {
            play();
        } else if (msg.type === 'pause') {
            pause();
        } else if (msg.type === 'ended') {
            ended();
        }
    };

    var player = new TcPlayer('player', options);

    var position = parseInt(lastPosition);

    /**
     * 过于接近结束位置当作已结束处理
     */
    if (position > 0 && player.duration() - position > 10) {
        player.currentTime(position);
    }

    function clearLearningInterval() {
        if (interval != null) {
            clearInterval(interval);
            interval = null;
        }
    }

    function setLearningInterval() {
        interval = setInterval(learning, intervalTime);
    }

    function play() {
        clearLearningInterval();
        setLearningInterval();
    }

    function pause() {
        clearLearningInterval();
    }

    function ended() {
        clearLearningInterval();
        learning();
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
                    position: player.currentTime(),
                }
            });
        }
    }

});