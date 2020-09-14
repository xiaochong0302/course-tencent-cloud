layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var interval = null;
    var intervalTime = 15000;
    var userId = window.user.id;
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

    console.log(options);

    options.listener = function (msg) {
        if (msg.type === 'play') {
            start();
        } else if (msg.type === 'pause') {
            stop();
        } else if (msg.type === 'end') {
            stop();
        }
    };

    var player = new TcPlayer('player', options);

    function start() {
        if (interval != null) {
            clearInterval(interval);
            interval = null;
        }
        interval = setInterval(learning, intervalTime);
    }

    function stop() {
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
                    chapter_id: chapterId,
                    request_id: requestId,
                    interval: intervalTime,
                    position: player.currentTime(),
                }
            });
        }
    }

});