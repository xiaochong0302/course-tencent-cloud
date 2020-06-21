<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>聊天记录</title>
    <link rel="stylesheet" href="/static/lib/layui/css/layui.css">
    <style>
        body .layim-chat-main {
            height: auto;
        }

        #LAY_page {
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="layim-chat-main">
    <ul id="LAY_view"></ul>
</div>

<div id="LAY_page"></div>

<textarea title="消息模版" id="LAY_tpl" style="display:none;">
<%# layui.each(d.data, function(index, item) {
  if (item.user.id == parent.layui.layim.cache().mine.id) { %>
    <li class="layim-chat-mine"><div class="layim-chat-user"><img src="<% item.user.avatar %>"><cite><i><% layui.data.date(item.timestamp) %></i><% item.user.name %></cite></div><div class="layim-chat-text"><% layui.layim.content(item.content) %></div></li>
  <%# } else { %>
    <li><div class="layim-chat-user"><img src="<% item.user.avatar %>"><cite><% item.user.name %><i><% layui.data.date(item.timestamp) %></i></cite></div><div class="layim-chat-text"><% layui.layim.content(item.content) %></div></li>
  <%# }
}); %>
</textarea>

<script src="/static/lib/layui/layui.js"></script>

<script>
    layui.use(['layim', 'laypage'], function () {

        var $ = layui.jquery;
        var layim = layui.layim;
        var laytpl = layui.laytpl;
        var laypage = layui.laypage;

        laytpl.config({
            open: '<%',
            close: '%>'
        });

        var chatHistoryUrl = '/im/chat/history';

        var currentUrl = layui.url();

        var params = {
            id: currentUrl.search.id,
            type: currentUrl.search.type,
            page: 1,
            limit: 15,
            sort: 'oldest'
        };

        loadChatHistoryHtml(params);

        laypage.render({
            elem: 'LAY_page',
            limit: 30,
            count: {{ pager.total_items }},
            jump: function (obj, first) {
                if (!first) {
                    params.page = obj.curr;
                    loadChatHistoryHtml(params);
                }
            }
        });

        function loadChatHistoryHtml(params) {
            $.get(chatHistoryUrl, params, function (res) {
                var html = laytpl(LAY_tpl.value).render({
                    data: res.items
                });
                $('#LAY_view').html(html);
            });
        }

    });
</script>

</body>
</html>
