<div class="user-list clearfix">
    <div class="layui-row layui-col-space20">
        {% for user in users %}
            {% set user.title = user.title ? user.title : '暂露头角' %}
            {% set user.about = user.about ? user.about : '这个人很懒，什么都没留下' %}
            {% set user_url = url({'for':'desktop.user.show','id':user.id}) %}
            <div class="layui-col-md2">
                <div class="user-card">
                    {{ vip_info(user.vip) }}
                    <div class="avatar">
                        <a href="{{ user_url }}" title="{{ user.about }}" target="user">
                            <img src="{{ user.avatar }}" alt="{{ user.name }}">
                        </a>
                    </div>
                    <div class="name layui-elip">
                        <a href="{{ user_url }}" title="{{ user.about }}" target="user">{{ user.name }}</a>
                    </div>
                    <div class="title layui-elip">{{ user.title }}</div>
                    <div class="action">
                        <span class="layui-btn apply-friend" data-id="{{ user.id }}" data-name="{{ user.name }}" data-avatar="{{ user.avatar }}">添加好友</span>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
</div>