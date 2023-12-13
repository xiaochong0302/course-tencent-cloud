layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var interval = null;
    var intervalTime = 15000;
    var userId = window.user.id;
    var requestId = helper.getRequestId();
    var chapterId = $('input[name="chapter.id"]').val();
    var cover = $('input[name="chapter.cover"]').val();
    var planId = $('input[name="chapter.me.plan_id"]').val();
    var position = $('input[name="chapter.me.position"]').val();
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
            quality.push({
                name: rate.label,
                url: playUrls[rate.name]['url'],
            });
        }
    });

    var player = new DPlayer({
        container: document.getElementById('player'),
        video: {
            pic: cover,
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

    /**
     * 播放器中央播放按钮
     */
    var $playMask = $('#play-mask');

    $playMask.on('click', function () {
        $(this).hide();
        player.toggle();
    });

    var lastPosition = getLastPosition();

    /**
     * 上次播放位置
     */
    if (lastPosition > 0) {
        player.seek(lastPosition);
    }

    function getPositionKey() {
        return 'chapter:' + chapterId + ':position';
    }

    function getLastPosition() {
        var key = getPositionKey();
        var value = localStorage.getItem(key);
        return value != null ? parseInt(value) : position;
    }

    function setLastPosition(value) {
        var key = getPositionKey();
        localStorage.setItem(key, value);
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
        $playMask.hide();
        clearLearningInterval();
        setLearningInterval();
    }

    function pause() {
        $playMask.show();
        clearLearningInterval();
    }

    function ended() {
        learning();
        setLastPosition(0);
        clearLearningInterval();
    }

    function learning() {
        setLastPosition(player.video.currentTime);
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