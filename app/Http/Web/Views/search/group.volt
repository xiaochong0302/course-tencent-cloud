<div class="search-group-list">
    {% for item in pager.items %}
        {% set group_url = url({'for':'web.group.show','id':item.id}) %}
        {% set owner_url = url({'for':'web.user.show','id':item.owner.id}) %}
        {% set item.about = item.about ? item.about : '这个家伙真懒，什么也没有留下！' %}
        <div class="search-group-card clearfix">
            <div class="avatar">
                <a href="{{ group_url }}">
                    <img src="{{ item.avatar }}" alt="{{ item.name }}">
                </a>
            </div>
            <div class="info">
                <div class="name layui-elip">
                    <a href="{{ group_url }}">{{ item.name }}</a>
                </div>
                <div class="about layui-elip">{{ item.about }}</div>
                <div class="meta">
                    <span>组长：<a href="{{ owner_url }}">{{ item.owner.name }}</a></span>
                    <span>成员：{{ item.user_count }}</span>
                </div>
            </div>
        </div>
    {% endfor %}
</div>
