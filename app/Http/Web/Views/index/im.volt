{% extends 'templates/full.volt' %}

{% block content %}

{% endblock %}

{% block inline_js %}

    {{ js_include('lib/layui/layui.js') }}

    <script>

        layui.use(['jquery', 'layim'], function () {

            var bindUserUrl = '/live/42425/bind';
            var sendMessageUrl = '/live/42425/message';

            var socket = new WebSocket('ws://127.0.0.1:8282');
            var $ = layui.jquery;
            var layim = layui.layim;

            layim.config({
                brief: true,
                init: {
                    mine: {
                        'username': '直飞机',
                        'avatar': 'http://tp1.sinaimg.cn/5619439268/180/40030060651/1',
                        'status': 'online',
                        'sign': '高舍炮打的准',
                        'id': 1,
                    }
                },
                members: {
                    url: '/live/members',
                    data: {
                        chapter_id: 123
                    }
                }
            }).chat({
                name: '直播讨论',
                type: 'group',
                avatar: 'http://tp1.sinaimg.cn/5619439268/180/40030060651/1',
                id: 123,
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
                    url: sendMessageUrl,
                    data: {from: from, to: to}
                });
            }

            function showMessage(message) {
                layim.getMessage(message);
            }

        });

    </script>

{% endblock %}