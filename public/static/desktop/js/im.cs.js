layui.use(['jquery', 'layim'], function () {

    var $ = layui.jquery;
    var layim = layui.layim;

    var me = {
        id: window.user.id,
        name: window.user.name,
        avatar: window.user.avatar
    };

    var csUser = {
        id: $('input[name="cs_user.id"]').val(),
        name: $('input[name="cs_user.name"]').val(),
        avatar: $('input[name="cs_user.avatar"]').val(),
        welcome: $('input[name="cs_user.welcome"]').val()
    };

    var socket = new WebSocket(window.im.websocket.url);

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
            bindUser(data);
        } else if (data.type === 'show_chat_msg') {
            showChatMessage(data);
        }
    };

    layim.config({
        init: {
            mine: {
                id: me.id,
                username: me.name,
                avatar: me.avatar,
                status: 'online',
            }
        },
        brief: true,
        maxLength: 1000,
    });

    layim.chat({
        id: csUser.id,
        name: csUser.name,
        avatar: csUser.avatar,
        type: 'friend',
    });

    layim.on('sendMessage', function (res) {
        sendCsMessage(res);
    });

    showWelcomeMessage(csUser);

    function bindUser(res) {
        $.ajax({
            type: 'POST',
            url: '/im/user/bind',
            data: {client_id: res.client_id}
        });
    }

    function sendCsMessage(res) {
        $.ajax({
            type: 'POST',
            url: '/im/msg/cs/send',
            data: {from: res.mine, to: res.to}
        });
    }

    function showChatMessage(res) {
        layim.getMessage(res.message);
    }

    function showWelcomeMessage(csUser) {
        layim.getMessage({
            id: csUser.id,
            username: csUser.name,
            avatar: csUser.avatar,
            content: csUser.welcome,
            timestamp: new Date().getTime(),
            type: 'friend',
        });
    }

});