layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;
    var socket = new WebSocket(window.koogua.socketUrl);

    var $chatMsgList = $('#chat-msg-list');

    socket.onopen = function () {
        console.log('socket connect success');
    };

    socket.onclose = function () {
        console.log('socket connect close');
    };

    socket.onerror = function () {
        console.log('socket connect error');
    };

    socket.onmessage = function (e) {
        var data = JSON.parse(e.data);
        console.log(data);
        if (data.type === 'ping') {
            socket.send('pong...');
        } else if (data.type === 'bind_user') {
            bindUser(data.client_id);
        } else if (data.type === 'show_message') {
            showMessage(data.content);
        }
    };

    form.on('submit(chat)', function (data) {
        $.ajax({
            type: 'POST',
            url: data.form.action,
            data: data.field,
            success: function (res) {
                showMessage(res);
            }
        });
        return false;
    });

    refreshLiveStats();

    setInterval('refreshLiveStats()', 60000);

    function bindUser(clientId) {
        $.ajax({
            type: 'POST',
            url: '/live/bind',
            data: {client_id: clientId}
        });
    }

    function showMessage(res) {
        var html = '<div class="chat">';
        html += '<span class="user">' + res.user.name + '</span>';
        if (res.user.vip === 1) {
            html += '<span class="layui-badge">VIP</span>';
        }
        html += '<span class="content">' + res.content + '</span>';
        html += '</div>';
        $chatMsgList.append(html);
    }

    function refreshLiveStats() {
        var $liveStats = $('#live-stats');
        helper.ajaxLoadHtml($liveStats.data('url'), $liveStats.attr('id'));
    }

});