{% if pager.total_pages > 0 %}
    <div class="tag-list">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                {% set follow_url = url({'for':'home.tag.follow','id':item.id}) %}
                {% set follow_class = item.me.followed == 1 ? 'layui-btn layui-btn-sm followed btn-follow' : 'layui-btn layui-btn-primary layui-btn-sm btn-follow' %}
                {% set follow_text = item.me.followed == 1 ? '已关注' : '关注' %}
                <div class="layui-col-md3">
                    <div class="tag-card">
                        <div class="icon">
                            <img src="{{ item.icon }}" alt="{{ item.name }}">
                        </div>
                        <div class="name">{{ item.name }}<span class="stats">（{{ item.follow_count }} 关注 ）</span></div>
                        <div class="action">
                            <span class="{{ follow_class }}" data-url="{{ follow_url }}">{{ follow_text }}</span>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}
