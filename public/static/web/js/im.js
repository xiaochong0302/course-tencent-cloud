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
            bindUser(data);
            showSystemMessage();
        } else if (data.type === 'show_chat_msg') {
            showChatMessage(data);
        } else if (data.type === 'show_sys_msg') {
            showSystemMessage();
        } else if (data.type === 'friend_accepted') {
            friendAccepted(data);
            showSystemMessage();
        } else if (data.type === 'group_accepted') {
            groupAccepted(data);
            showSystemMessage();
        }
    };

    layim.config({
        title: '菜鸟驿站',
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
        sendChatMessage(res);
    });

    layim.on('sign', function (sign) {
        $.ajax({
            type: 'POST',
            url: '/im/sign/update',
            data: {sign: sign}
        });
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

    function showSystemMessage() {
        $.ajax({
            type: 'GET',
            url: '/im/msg/unread/count',
            success: function (res) {
                if (res.count > 0) {
                    layim.msgbox(res.count);
                }
            }
        });
    }

    function friendAccepted(res) {
        layim.addList({
            type: 'friend',
            groupid: res.group.id,
            username: res.friend.name,
            avatar: res.friend.avatar,
            id: res.friend.id
        });
    }

    function groupAccepted(res) {
        layim.addList({
            type: 'group',
            groupname: res.group.name,
            avatar: res.group.avatar,
            id: res.group.id
        });
    }

});