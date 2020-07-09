layui.use(['jquery', 'form', 'helper'], function () {

    var $ = layui.jquery;
    var form = layui.form;
    var helper = layui.helper;

    var interval = null;
    var intervalTime = 15000;
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
        height: 428
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

    if (position > 0) {
        player.currentTime(position);
    }

    $('#danmu').danmu({
        left: 20,
        top: 20,
        width: 750,
        height: 380
    });

    //再添加三个弹幕
    $("#danmu").danmu("addDanmu", [
        {text: "这是滚动弹幕", color: "white", size: 0, position: 0, time: 120}
        , {text: "这是顶部弹幕", color: "yellow", size: 0, position: 1, time: 120}
        , {text: "这是底部弹幕", color: "red", size: 0, position: 2, time: 120}
    ]);

    form.on('checkbox(status)', function (data) {
        if (data.elem.checked) {
            $('#danmu').danmu('setOpacity', 1);
        } else {
            $('#danmu').danmu('setOpacity', 0);
        }
    });

    form.on('submit(chat)', function (data) {
        $.ajax({
            type: 'POST',
            url: data.form.action,
            data: {
                text: data.field.text,
                time: player.currentTime(),
                chapter_id: chapterId,
            },
            success: function (res) {
                showDanmu(res);
            }
        });
        return false;
    });

    function start() {
        if (interval != null) {
            clearInterval(interval);
            interval = null;
        }
        interval = setInterval(learning, intervalTime);
        startDanmu();
    }

    function stop() {
        clearInterval(interval);
        interval = null;
        pauseDanmu();
    }

    function learning() {
        if (userId !== '0' && planId !== '0') {
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
    }

    function startDanmu() {
        $('#danmu').danmu('danmuStart');
    }

    function pauseDanmu() {
        $('#danmu').danmu('danmuPause');
    }

    function showDanmu(res) {
        /*
        $('#danmu').danmu('addDanmu', {
            text: res.danmu.text,
            color: res.danmu.color,
            size: res.danmu.size,
            time: res.danmu.time,
            position: res.danmu.position,
            isnew: 1
        });
        */
        $("#danmu").danmu("addDanmu", [
            {text: "这是滚动弹幕", color: "white", size: 0, position: 0, time: 300}
            , {text: "这是顶部弹幕", color: "yellow", size: 0, position: 0, time: 300}
            , {text: "这是底部弹幕", color: "red", size: 0, position: 0, time: 300}
        ]);
        $('input[name=text]').val('');
    }

});