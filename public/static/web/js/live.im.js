layui.use(['jquery', 'layim'], function () {

    var $ = layui.jquery;
    var layim = layui.layim;

    var socket = new WebSocket('ws://127.0.0.1:8282');

    var membersUrl = $('input[name="im.members_url"]').val();
    var bindUserUrl = $('input[name="im.bind_user_url"]').val();
    var sendMsgUrl = $('input[name="im.send_msg_url"]').val();

    var group = {
        id: $('input[name="chapter.id"]').val(),
        avatar: 'http://tp1.sinaimg.cn/5619439268/180/40030060651/1',
        name: '直播讨论'
    };

    var user = {
        id: $('input[name="user.id"]').val(),
        name: $('input[name="user.name"]').val(),
        avatar: $('input[name="user.avatar"]').val(),
        status: 'online',
        sign: ''
    };

    layim.config({
        brief: true,
        init: {
            mine: {
                'username': user.name,
                'avatar': user.avatar,
                'id': user.id,
                'status': user.status,
                'sign': user.sign
            }
        },
        members: {
            url: membersUrl
        }
    }).chat({
        type: 'group',
        name: group.name,
        avatar: group.avatar,
        id: group.id
    });

    layim.on('sendMessage', function (res) {
        sendMessage(res.mine, res.to);
    });

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

    function bindUser(clientId) {
        $.ajax({
            type: 'POST',
            url: bindUserUrl,
            data: {client_id: clientId}
        });
    }

    function sendMessage(from, to) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: sendMsgUrl,
            data: {from: from, to: to}
        });
    }

    function showMessage(message) {
        layim.getMessage(message);
    }

});