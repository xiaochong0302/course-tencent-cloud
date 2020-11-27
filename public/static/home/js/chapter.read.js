layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var interval = null;
    var intervalTime = 15000;
    var userId = window.user.id;
    var planId = $('input[name="chapter.plan_id"]').val();
    var learningUrl = $('input[name="chapter.learning_url"]').val();
    var requestId = helper.getRequestId();

    if (userId !== '0' && planId !== '0') {
        start();
        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'hidden') {
                stop();
            } else if (document.visibilityState === 'visible') {
                start();
            }
        });
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
                plan_id: planId,
                request_id: requestId,
                interval_time: intervalTime,
            }
        });
    }

});