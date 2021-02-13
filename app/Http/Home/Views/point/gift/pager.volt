{{ partial('macros/point') }}

{% if pager.total_pages > 0 %}
    <div class="course-list clearfix">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                {% set gift_url = url({'for':'home.point_gift.show','id':item.id}) %}
                <div class="layui-col-md3">
                    <div class="course-card">
                        <div class="cover">
                            <a href="{{ gift_url }}">
                                <img src="{{ item.cover }}" alt="item.name">
                            </a>
                        </div>
                        <div class="info">
                            <div class="title layui-elip">
                                <a href="{{ gift_url }}">{{ item.name }}</a>
                            </div>
                            <div class="meta">
                                {{ gift_type_info(item.type) }}
                                <span class="price">{{ item.point }} 积分</span>
                                <span>{{ item.redeem_count }} 人兑换</span>
                            </div>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}