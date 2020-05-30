<div class="package-list">
    {% for package in packages %}
        {% set order_url = url({'for':'web.order.confirm'},{'item_id':package.id,'item_type':'package'}) %}
        <div class="package-item clearfix">
            <div class="package-info">
                <div class="title">{{ package.title }}</div>
                <div class="origin-price">
                    <span>{{ package.course_count }} 门课程</span>
                    <span>总价 <i>￥{{ package.origin_price }}</i></span>
                </div>
                <div class="price">
                    <span>市场价 <i>￥{{ package.market_price }}</i></span>
                    <span>会员价 <i>￥{{ package.vip_price }}</i></span>
                </div>
                <div class="order">
                    <a class="layui-btn layui-btn-sm layui-bg-red" href="{{ order_url }}">立即购买</a>
                </div>
            </div>
            <div class="package-course-list">
                {% for course in package.courses %}
                    {% set course_url = url({'for':'web.course.show','id':course.id}) %}
                    <div class="package-course-card">
                        <div class="cover"><img src="{{ course.cover }}!cover_270" alt="{{ course.title }}"></div>
                        <div class="title"><a href="{{ course_url }}">{{ course.title }}</a></div>
                    </div>
                {% endfor %}
            </div>
        </div>
    {% endfor %}
</div>