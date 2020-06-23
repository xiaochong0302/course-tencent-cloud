layui.use(['jquery', 'layim'], function () {

    var $ = layui.jquery;
    var layim = layui.layim;
    var socket = new WebSocket('ws://127.0.0.1:8282');

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
        } else if (data.type === 'show_chat_msg') {
            showChatMessage(data.content);
        } else if (data.type === 'show_msg_box') {
            showMessageBox(data.content);
        }
    };

    layim.config({
        title: '即时聊天',
        init: {
            url: '/im/init'
        },
        members: {
            url: '/im/group/members'
        },
        uploadImage: {
            url: '/im/img/upload'
        },
        uploadFile: {
            url: '/im/file/upload'
        },
        maxLength: 1000,
        find: '/im/find',
        msgbox: '/im/msg/box',
        chatLog: '/im/chat/log'
    });

    layim.on('sendMessage', function (res) {
        sendChatMessage(res.mine, res.to);
    });

    layim.on('sign', function (sign) {
        $.ajax({
            type: 'POST',
            url: '/im/sign/update',
            data: {sign: sign}
        });
    });

    function bindUser(clientId) {
        $.ajax({
            type: 'POST',
            url: '/im/user/bind',
            data: {client_id: clientId}
        });
    }

    function sendChatMessage(from, to) {
        $.ajax({
            type: 'POST',
            url: '/im/msg/send',
            data: {from: from, to: to}
        });
    }

    function showChatMessage(content) {
        layim.getMessage(content);
    }

    function showMessageBox(content) {
        layim.msgbox(content.msg_count);
    }

});