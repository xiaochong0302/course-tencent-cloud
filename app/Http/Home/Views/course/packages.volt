<div class="package-list">
    {% for package in packages %}
        {% set order_url = url({'for':'home.order.confirm'},{'item_id':package.id,'item_type':2}) %}
        <div class="package-item">
            <div class="package-info">
                <div class="title">{{ package.title }}</div>
                <div class="origin-price">
                    <span>{{ package.course_count }} 门课程</span>
                    <span>总价 <i>{{ '￥%0.2f'|format(package.origin_price) }}</i></span>
                </div>
                <div class="price">
                    <span>优惠价 <i>{{ '￥%0.2f'|format(package.market_price) }}</i></span>
                </div>
                <div class="price">
                    <span>会员价 <i>{{ '￥%0.2f'|format(package.vip_price) }}</i></span>
                </div>
                <div class="order">
                    <a class="layui-btn layui-btn-sm layui-bg-red btn-buy" href="javascript:" data-url="{{ order_url }}">立即购买</a>
                </div>
            </div>
            <div class="package-course-list">
                {% for course in package.courses %}
                    {% set course_url = url({'for':'home.course.show','id':course.id}) %}
                    <div class="package-course-card">
                        <div class="cover"><img src="{{ course.cover }}!cover_270" alt="{{ course.title }}"></div>
                        <div class="title"><a href="{{ course_url }}" target="_blank">{{ course.title }}</a></div>
                    </div>
                    {% if loop.first %}
                        <div class="separator"><i class="layui-icon layui-icon-add-1"></i></div>
                    {% endif %}
                {% endfor %}
            </div>
        </div>
    {% endfor %}
</div>