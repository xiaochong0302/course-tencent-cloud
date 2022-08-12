{% if pager.total_pages > 0 %}
    <div class="tag-list">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                {% set follow_url = url({'for':'home.tag.follow','id':item.id}) %}
                <div class="layui-col-md3">
                    <div class="tag-card">
                        <div class="icon">
                            <img src="{{ item.icon }}" alt="{{ item.name }}">
                        </div>
                        <div class="name">{{ item.name }}<span class="stats">（{{ item.follow_count }} 关注 ）</span></div>
                        <div class="action">
                            <span class="layui-btn layui-btn-sm followed btn-follow" data-url="{{ follow_url }}">已关注</span>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}
