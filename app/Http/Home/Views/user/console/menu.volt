<div class="layui-card">
    <div class="layui-card-header">学习内容</div>
    <div class="layui-card-body">
        <ul class="my-menu">
            <li><a href="{{ url({'for':'home.uc.study_courses'}) }}">在学课程</a></li>
            <li><a href="{{ url({'for':'home.uc.favorites'}) }}">我的收藏</a></li>
        </ul>
    </div>
</div>

<div class="layui-card">
    <div class="layui-card-header">用户服务</div>
    <div class="layui-card-body">
        <ul class="my-menu">
            <li><a href="{{ url({'for':'home.uc.orders'}) }}">我的订单</a></li>
            <li><a href="{{ url({'for':'home.uc.refunds'}) }}">我的退款</a></li>
            <li><a href="{{ url({'for':'home.uc.reviews'}) }}">我的评价</a></li>
        </ul>
    </div>
</div>

<div class="layui-card">
    <div class="layui-card-header">个人设置</div>
    <div class="layui-card-body">
        <ul class="my-menu">
            <li><a href="{{ url({'for':'home.uc.profile'}) }}">个人信息</a></li>
            <li><a href="{{ url({'for':'home.uc.account'}) }}">帐号安全</a></li>
        </ul>
    </div>
</div>
