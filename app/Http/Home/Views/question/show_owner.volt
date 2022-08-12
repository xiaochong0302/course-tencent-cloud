<div class="layui-card">
    <div class="layui-card-header">关于作者</div>
    <div class="layui-card-body">
        <div class="sidebar-user-card">
            <div class="avatar">
                <img src="{{ question.owner.avatar }}!avatar_160" alt="{{ question.owner.name }}">
            </div>
            <div class="info">
                <div class="name layui-elip">
                    <a href="{{ question_owner_url }}" title="{{ question.owner.about }}">{{ question.owner.name }}</a>
                </div>
                <div class="title layui-elip">{{ question.owner.title|default('初出江湖') }}</div>
            </div>
        </div>
    </div>
</div>