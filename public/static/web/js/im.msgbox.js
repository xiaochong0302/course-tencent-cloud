layui.use(['jquery', 'layer', 'layim', 'laypage'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var layim = layui.layim;
    var laypage = layui.laypage;

    var $target = $('#LAY_view');
    var $page = $('#LAY_page');
    var count = $page.data('count');
    var limit = 15;

    /**
     * 标记信息为已读
     */
    markMessageAsRead();

    /**
     * 加载第一页数据
     */
    loadPageHtml($target, 1);

    /**
     * 两页以上才显示分页
     */
    if (count > limit) {
        laypage.render({
            elem: $page.attr('id'),
            limit: limit,
            count: count,
            jump: function (obj, first) {
                if (!first) {
                    loadPageHtml($target, obj.curr);
                }
            }
        });
    }

    function loadPageHtml(target, page) {
        $.get('/im/msg/sys', {page: page}, function (html) {
            target.html(html);
        });
    }

    function markMessageAsRead() {
        $.post('/im/msg/read');
    }

    //操作
    var active = {
        //同意
        agree: function (othis) {
            var li = othis.parents('li')
                , uid = li.data('uid')
                , from_group = li.data('fromGroup')
                , user = cache[uid];

            //选择分组
            parent.layui.layim.setFriendGroup({
                type: 'friend'
                , username: user.username
                , avatar: user.avatar
                , group: parent.layui.layim.cache().friend //获取好友分组数据
                , submit: function (group, index) {

                    //将好友追加到主面板
                    parent.layui.layim.addList({
                        type: 'friend'
                        , avatar: user.avatar //好友头像
                        , username: user.username //好友昵称
                        , groupid: group //所在的分组id
                        , id: uid //好友ID
                        , sign: user.sign //好友签名
                    });
                    parent.layer.close(index);
                    othis.parent().html('已同意');


                    //实际部署时，请开启下述注释，并改成你的接口地址
                    /*
                    $.post('/im/agreeFriend', {
                      uid: uid //对方用户ID
                      ,from_group: from_group //对方设定的好友分组
                      ,group: group //我设定的好友分组
                    }, function(res){
                      if(res.code != 0){
                        return layer.msg(res.msg);
                      }

                      //将好友追加到主面板
                      parent.layui.layim.addList({
                        type: 'friend'
                        ,avatar: user.avatar //好友头像
                        ,username: user.username //好友昵称
                        ,groupid: group //所在的分组id
                        ,id: uid //好友ID
                        ,sign: user.sign //好友签名
                      });
                      parent.layer.close(index);
                      othis.parent().html('已同意');
                    });
                    */

                }
            });
        }

        //拒绝
        , refuse: function (othis) {
            var li = othis.parents('li')
                , uid = li.data('uid');

            layer.confirm('确定拒绝吗？', function (index) {
                $.post('/im/refuseFriend', {
                    uid: uid //对方用户ID
                }, function (res) {
                    if (res.code != 0) {
                        return layer.msg(res.msg);
                    }
                    layer.close(index);
                    othis.parent().html('<em>已拒绝</em>');
                });
            });
        }
    };

    $('body').on('click', '.layui-btn', function () {
        var othis = $(this), type = othis.data('type');
        active[type] ? active[type].call(this, othis) : '';
    });

});