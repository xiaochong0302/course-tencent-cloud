{% if questions|length > 0 %}
    <div class="layui-card">
        <div class="layui-card-header">热门问题</div>
        <div class="layui-card-body">
            {% for item in questions %}
                {% set url = url({'for':'home.question.show','id':item.id}) %}
                {% set rank = loop.index %}
                <div class="sidebar-rank-card">
                    <div class="rank">
                        {% if rank == 1 %}
                            <span class="layui-badge layui-bg-red">{{ rank }}</span>
                        {% elseif rank == 2 %}
                            <span class="layui-badge layui-bg-blue">{{ rank }}</span>
                        {% elseif rank == 3 %}
                            <span class="layui-badge layui-bg-green">{{ rank }}</span>
                        {% else %}
                            <span class="layui-badge layui-bg-gray">{{ rank }}</span>
                        {% endif %}
                    </div>
                    <div class="title">
                        <a href="{{ url }}">{{ item.title }}</a>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
{% endif %}
