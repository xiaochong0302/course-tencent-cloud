layui.use(['jquery', 'layim'], function () {

    var $ = layui.jquery;
    var layim = layui.layim;
    var socket = new WebSocket(window.koogua.im.socket_url);

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
        brief: true,
        init: {
            mine: {
                id: "100000123",
                username: "访客",
                avatar: "//res.layui.com/images/fly/avatar/00.jpg",
                status: "online",
            }
        },
        maxLength: 1000,
    });

    layim.chat({
        type: 'friend',
        id: 1111111,
        name: '在线客服一',
        avatar: '//tp1.sinaimg.cn/5619439268/180/40030060651/1',
    });

    layim.on('sendMessage', function (res) {
        sendChatMessage(res);
    });

    function bindUser(res) {
        $.ajax({
            type: 'POST',
            url: '/im/user/bind',
            data: {client_id: res.client_id}
        });
    }

    function sendChatMessage(res) {
        $.ajax({
            type: 'POST',
            url: '/im/msg/send',
            data: {from: res.mine, to: res.to}
        });
    }

    function showChatMessage(res) {
        layim.getMessage(res.message);
    }

});