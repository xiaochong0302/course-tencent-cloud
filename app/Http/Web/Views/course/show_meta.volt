<div class="cover">
    <img src="{{ course.cover }}" alt="{{ course.title|e }}">
</div>

<div class="info">
    {% if course.model == 'vod' %}
        <p>课程时长 <span>{{ course.attrs.duration|total_duration }}</span></p>
    {% elseif course.model == 'live' %}
        <p>直播时间 <span>{{ course.attrs.start_date }} ~ {{ course.attrs.end_date }}</span></p>
    {% endif %}
    {% if course.market_price > 0 %}
        <p>
            学习期限 <span class="expiry">{{ course.study_expiry }}个月</span>
            退款期限 <span class="expiry">{{ course.refund_expiry }}天</span>
        </p>
    {% endif %}
    <p>
        {% if course.market_price > 0 %}
            市场价格 <span class="price">{{ '￥%0.2f'|format(course.market_price) }}</span>
        {% else %}
            市场价格 <span class="free">免费</span>
        {% endif %}
        {% if course.vip_price > 0 %}
            会员价格 <span class="price">{{ '￥%0.2f'|format(course.vip_price) }}</span>
        {% else %}
            会员价格 <span class="free">免费</span>
        {% endif %}
    </p>
    <p>
        难度 <span>{{ level_info(course.level) }}</span>
        课时 <span>{{ course.lesson_count }}</span>
        学员 <span>{{ course.user_count }}</span>
        评分 <span>{{ course.rating }}</span>
    </p>

    {% set favorite_url = url({'for':'web.course.favorite','id':course.id}) %}
    {% set full_course_url = full_url({'for':'web.course.show','id':course.id}) %}
    {% set qrcode_url = url({'for':'web.qrcode_img'},{'text':full_course_url}) %}

    <div class="layui-hide">
        <input type="hidden" name="share.title" value="{{ course.title }}">
        <input type="hidden" name="share.pic" value="{{ course.cover }}">
        <input type="hidden" name="share.url" value="{{ full_course_url }}">
        <input type="hidden" name="share.qrcode" value="{{ qrcode_url }}">
    </div>

    <div class="share">
        <a href="javascript:" title="收藏" data-url="{{ favorite_url }}"><i class="layui-icon layui-icon-heart icon-heart"></i></a>
        <a href="javascript:" title="分享到微信" data-url="{{ qrcode_url }}"><i class="layui-icon layui-icon-login-wechat icon-wechat"></i></a>
        <a href="javascript:" title="分享到QQ空间"><i class="layui-icon layui-icon-login-qq icon-qq"></i></a>
        <a href="javascript:" title="分享到微博"><i class="layui-icon layui-icon-login-weibo icon-weibo"></i></a>
    </div>
</div>