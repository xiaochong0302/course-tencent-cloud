<div class="breadcrumb">
    <span class="layui-breadcrumb">
        <a class="kg-back" href="javascript:"><i class="layui-icon layui-icon-return"></i> 返回课程</a>
        <a><cite>{{ chapter.course.title }}</cite></a>
        <a><cite>{{ chapter.title }}</cite></a>
    </span>
    <span class="share">
        <a href="javascript:" title="学习人次"><i class="layui-icon layui-icon-user"></i><em>{{ chapter.user_count }}</em></a>
        <a href="javascript:" title="我要点赞" data-url="{{ like_url }}"><i class="layui-icon layui-icon-praise icon-praise {{ liked_class }}"></i><em class="like-count">{{ chapter.like_count }}</em></a>
        <a href="javascript:" title="我要提问" data-url="{{ consult_url }}"><i class="layui-icon layui-icon-help icon-help"></i><em>{{ chapter.consult_count }}</em></a>
        <a href="javascript:" title="分享到微信"><i class="layui-icon layui-icon-login-wechat icon-wechat"></i></a>
        <a href="javascript:" title="分享到QQ空间"><i class="layui-icon layui-icon-login-qq icon-qq"></i></a>
        <a href="javascript:" title="分享到微博"><i class="layui-icon layui-icon-login-weibo icon-weibo"></i></a>
    </span>
</div>