layui.use(['jquery', 'form', 'helper'], function () {

    var $ = layui.jquery;
    var form = layui.form;
    var helper = layui.helper;
    var socket = new WebSocket(window.websocket.connect_url);
    var bindUserUrl = $('input[name="bind_user_url"]').val();
    var liveStatsUrl = $('input[name="live_stats_url"]').val();
    var $chatContent = $('input[name=content]');
    var $chatMsgList = $('#chat-msg-list');

    socket.onopen = function () {
        console.log('socket connect success');
        setInterval(function () {
            socket.send('ping');
            console.log('ping...');
        }, 1000 * parseInt(window.websocket.ping_interval));
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
        if (data.type === 'bind_user') {
            bindUser(data.client_id);
        } else if (data.type === 'new_message') {
            showNewMessage(data);
        } else if (data.type === 'new_user') {
            showLoginMessage(data);
        }
    };

    form.on('submit(chat)', function (data) {
        $.ajax({
            type: 'POST',
            url: data.form.action,
            data: data.field,
            success: function (res) {
                showNewMessage(res);
                $chatContent.val('');
            }
        });
        return false;
    });

    loadRecentChats();

    refreshLiveStats();

    setInterval(function () {
        refreshLiveStats();
    }, 30000);

    function bindUser(clientId) {
        $.ajax({
            type: 'POST',
            url: bindUserUrl,
            data: {client_id: clientId}
        });
    }

    function showNewMessage(res) {
        var html = '';
        if (res.user.vip === 1) {
            html = getVipUserMessage(res);
        } else {
            html = getNormalUserMessage(res);
        }
        $chatMsgList.append(html);
        scrollToBottom();
    }

    function showLoginMessage(res) {
        var html = '<div class="chat chat-sys">';
        html += '<span class="icon"><i class="layui-icon layui-icon-speaker"></i></span>';
        html += '<span>' + res.user.name + '</span>';
        html += '<span>进入了直播间</span>';
        html += '</div>';
        $chatMsgList.append(html);
        scrollToBottom();
    }

    function getVipUserMessage(res) {
        var html = '<div class="chat chat-vip">';
        html += '<span class="icon"><i class="layui-icon layui-icon-diamond"></i></span>';
        html += '<span class="user layui-badge layui-bg-orange">' + res.user.name + '</span>';
        html += '<span class="content">' + res.content + '</span>';
        html += '</div>';
        return html;
    }

    function getNormalUserMessage(res) {
        var html = '<div class="chat chat-normal">';
        html += '<span class="icon"><i class="layui-icon layui-icon-username"></i></span>';
        html += '<span class="user layui-badge layui-bg-blue">' + res.user.name + '</span>';
        html += '<span class="content">' + res.content + '</span>';
        html += '</div>';
        return html;
    }

    function scrollToBottom() {
        var $scrollTo = $chatMsgList.find('.chat:last');
        $chatMsgList.scrollTop($scrollTo.offset().top - $chatMsgList.offset().top + $chatMsgList.scrollTop());
    }

    function refreshLiveStats() {
        var $count = $('#toolbar-online > .text');
        $.get(liveStatsUrl, function (res) {
            $count.text(res.stats.client_count);
        });
    }

    function loadRecentChats() {
        helper.ajaxLoadHtml($chatMsgList.data('url'), $chatMsgList.attr('id'));
    }

});
