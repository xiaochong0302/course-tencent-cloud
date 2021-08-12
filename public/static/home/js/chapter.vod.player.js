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

    var rates = [
        {name: 'hd', label: '高清'},
        {name: 'sd', label: '标清'},
        {name: 'fd', label: '极速'},
    ];

    var quality = [];

    $.each(rates, function (k, rate) {
        if (playUrls.hasOwnProperty(rate.name)) {
            quality[k] = {
                name: rate.label,
                url: playUrls[rate.name]['url'],
            };
        }
    });

    var player = new DPlayer({
        container: document.getElementById('player'),
        video: {
            quality: quality,
            defaultQuality: 0,
        }
    });

    player.on('play', function () {
        play();
    });

    player.on('pause', function () {
        pause();
    });

    player.on('ended', function () {
        ended();
    });

    var position = parseInt(lastPosition);

    /**
     * 过于接近结束位置当作已结束处理
     */
    if (position > 0 && player.video.duration - position > 10) {
        player.seek(position);
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
                    position: player.video.currentTime,
                }
            });
        }
    }

});