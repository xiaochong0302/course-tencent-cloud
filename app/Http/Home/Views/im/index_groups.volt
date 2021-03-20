<div class="user-list clearfix">
    <div class="layui-row layui-col-space20">
        {% for group in groups %}
            {% set group_url = url({'for':'home.im_group.show','id':group.id}) %}
            {% set group.about = group.about ? group.about : '这家伙真懒，什么都没留下！' %}
            <div class="layui-col-md3">
                <div class="user-card">
                    <span class="type layui-badge layui-bg-green">{{ type_info(group.type) }}</span>
                    <div class="avatar">
                        <a href="{{ group_url }}" title="{{ group.about }}" target="group">
                            <img src="{{ group.avatar }}!avatar_160" alt="{{ group.name }}">
                        </a>
                    </div>
                    <div class="name layui-elip">
                        <a href="{{ group_url }}" title="{{ group.name }}" target="group">{{ group.name }}</a>
                    </div>
                    <div class="meta layui-elip">
                        <span>成员：{{ group.user_count }}</span>
                        <span>讨论：{{ group.msg_count }}</span>
                    </div>
                    <div class="action">
                        <span class="layui-btn apply-group" data-id="{{ group.id }}" data-name="{{ group.name }}" data-avatar="{{ group.avatar }}">加入群组</span>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
</div>