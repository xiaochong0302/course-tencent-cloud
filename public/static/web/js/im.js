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
            refreshMessageBox();
        } else if (data.type === 'new_group_user') {
            showNewGroupUserMessage(data);
        } else if (data.type === 'show_online_tips') {
            showOnlineTips(data);
        } else if (data.type === 'show_chat_msg') {
            showChatMessage(data);
        } else if (data.type === 'refresh_msg_box') {
            refreshMessageBox();
        } else if (data.type === 'friend_accepted') {
            friendAccepted(data);
            refreshMessageBox();
        } else if (data.type === 'group_accepted') {
            groupAccepted(data);
            refreshMessageBox();
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

    layim.on('chatChange', function (res) {
        console.log(res);
    });

    layim.on('online', function (status) {
        $.ajax({
            type: 'POST',
            url: '/im/online/update',
            data: {status: status}
        });
    });

    layim.on('sign', function (sign) {
        $.ajax({
            type: 'POST',
            url: '/im/sign/update',
            data: {sign: sign}
        });
    });

    layim.on('setSkin', function (file, src) {
        $.ajax({
            type: 'POST',
            url: '/im/skin/update',
            data: {skin: src}
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

    function showNewGroupUserMessage(res) {
        var content = '<a href="/user/' + res.user.id + '" target="_blank">[' + res.user.name + ']</a> 加入群聊';
        layim.getMessage({
            system: true,
            type: 'group',
            id: res.group.id,
            content: content
        });
    }

    function refreshMessageBox() {
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

    function showOnlineTips(res) {
        var msg = res.friend.name + '上线了';
        layer.msg(msg, {
            icon: 6,
            offset: 'b',
            anim: 6
        });
        layim.setFriendStatus(res.friend.id, res.status);
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