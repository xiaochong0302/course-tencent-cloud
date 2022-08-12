<div class="layui-card">
    <div class="layui-card-header">关于作者</div>
    <div class="layui-card-body">
        <div class="sidebar-user-card">
            <div class="avatar">
                <img src="{{ article.owner.avatar }}!avatar_160" alt="{{ article.owner.name }}">
            </div>
            <div class="info">
                <div class="name layui-elip">
                    <a href="{{ article_owner_url }}" title="{{ article.owner.about }}">{{ article.owner.name }}</a>
                </div>
                <div class="title layui-elip">{{ article.owner.title|default('初出江湖') }}</div>
            </div>
        </div>
    </div>
</div>