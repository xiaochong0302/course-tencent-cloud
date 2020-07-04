layui.use(['jquery', 'form', 'helper'], function () {

    var $ = layui.jquery;
    var form = layui.form;
    var helper = layui.helper;
    var socket = new WebSocket(window.koogua.socketUrl);
    var bindUserUrl = $('input[name="bind_user_url"]').val();
    var $chatContent = $('input[name=content]');
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
    }, 300000);

    function bindUser(clientId) {
        $.ajax({
            type: 'POST',
            url: bindUserUrl,
            data: {client_id: clientId}
        });
    }

    function showNewMessage(res) {
        var html = '<div class="chat">';
        if (res.user.vip === 0) {
            html += '<span class="vip-icon layui-icon layui-icon-diamond"></span>';
        }
        html += '<span class="user">' + res.user.name + ':</span>';
        html += '<span class="content">' + res.content + '</span>';
        html += '</div>';
        $chatMsgList.append(html);
        scrollToBottom();
    }

    function showLoginMessage(res) {
        var html = '<div class="chat chat-sys">';
        html += '<span>' + res.user.name + '</span>';
        html += '<span>进入了直播间</span>';
        html += '</div>';
        $chatMsgList.append(html);
        scrollToBottom();
    }

    function scrollToBottom() {
        var $scrollTo = $chatMsgList.find('.chat:last');
        $chatMsgList.scrollTop(
            $scrollTo.offset().top - $chatMsgList.offset().top + $chatMsgList.scrollTop()
        );
    }

    function refreshLiveStats() {
        var $tabStats = $('#tab-stats');
        helper.ajaxLoadHtml($tabStats.data('url'), $tabStats.attr('id'));
    }

    function loadRecentChats() {
        helper.ajaxLoadHtml($chatMsgList.data('url'), $chatMsgList.attr('id'));
    }

});