layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var interval = null;
    var intervalTime = 15000;
    var userId = window.user.id;
    var requestId = helper.getRequestId();
    var chapterId = $('input[name="chapter.id"]').val();
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
            quality.push({
                name: rate.label,
                url: playUrls[rate.name]['url'],
            });
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

    /**
     * 播放器中央播放按钮点击事件
     */
    $('#play-mask').on('click', function () {
        $(this).hide();
        player.toggle();
    });

    var position = getLastPosition();

    /**
     * 上次播放位置
     */
    if (position > 0) {
        player.seek(position);
    }

    function getPositionKey() {
        return 'chapter:' + chapterId + ':position';
    }

    function getLastPosition() {
        var key = getPositionKey();
        var value = localStorage.getItem(key);
        return value != null ? parseInt(value) : lastPosition;
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
        clearLearningInterval();
        setLearningInterval();
    }

    function pause() {
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