{% if answerers|length > 0 %}
    <div class="layui-card">
        <div class="layui-card-header">热门答主</div>
        <div class="layui-card-body">
            <div class="sidebar-user-list">
                {% for author in answerers %}
                    {% set author.title = author.title ? author.title : '暂露头角' %}
                    {% set author_url = url({'for':'home.user.show','id':author.id}) %}
                    <div class="sidebar-user-card">
                        <div class="avatar">
                            <img src="{{ author.avatar }}!avatar_160" alt="{{ author.name }}">
                        </div>
                        <div class="info">
                            <div class="name layui-elip">
                                <a href="{{ author_url }}" title="{{ author.about }}" target="_blank">{{ author.name }}</a>
                            </div>
                            <div class="title layui-elip">{{ author.title }}</div>
                        </div>
                    </div>
                {% endfor %}
            </div>
        </div>
    </div>
{% endif %}